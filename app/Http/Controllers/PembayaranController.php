<?php
namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class PembayaranController extends Controller
{

    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        Log::info('Midtrans Configuration:', [
            'server_key_exists' => ! empty(config('midtrans.server_key')),
            'is_production'     => config('midtrans.is_production'),
        ]);
    }

    public function createPayment(Request $request, $pemesananId)
    {
        DB::beginTransaction();
        try {
            Log::info('Starting payment creation for pemesanan ID: ' . $pemesananId);

            $pemesanan = Pemesanan::with(['tiket', 'wisata', 'user'])
                ->findOrFail($pemesananId);

            Log::info('Pemesanan data:', [
                'id'          => $pemesanan->id,
                'total_harga' => $pemesanan->total_harga,
                'user'        => $pemesanan->user->username,
            ]);

            $orderId = 'ORDER-' . $pemesanan->id . '-' . time();

            $params = [
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => intval($pemesanan->total_harga),
                ],
                'customer_details'    => [
                    'first_name' => $pemesanan->user->username,
                    'email'      => $pemesanan->user->email,
                    'phone'      => $pemesanan->user->no_hp,
                ],
                'item_details'        => [
                    [
                        'id'       => 'TIKET-' . $pemesanan->tiket->id,
                        'price'    => intval($pemesanan->tiket->harga_tiket),
                        'quantity' => $pemesanan->jumlah_tiket,
                        'name'     => 'Tiket ' . $pemesanan->wisata->nama_wisata,
                    ],
                ],
            ];

            Log::info('Midtrans parameters:', $params);

            try {
                $snapToken = Snap::createTransaction($params)->token;
                Log::info('Snap Token generated:', ['token' => $snapToken]);
            } catch (\Exception $e) {
                Log::error('Snap Token generation failed:', ['error' => $e->getMessage()]);
                throw $e;
            }

            // Coba simpan pembayaran
            try {
                $pembayaran                     = new Pembayaran();
                $pembayaran->pemesanan_id       = $pemesanan->id;
                $pembayaran->order_id           = $orderId;
                $pembayaran->metode_pembayaran  = 'midtrans';
                $pembayaran->tanggal_pembayaran = now();
                $pembayaran->status             = 'belum_bayar';
                $pembayaran->save();

                Log::info('Payment record created:', [
                    'id'       => $pembayaran->id,
                    'order_id' => $pembayaran->order_id,
                    'status'   => $pembayaran->status,
                ]);
            } catch (\Exception $e) {
                Log::error('Payment record creation failed:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                throw $e;
            }

            DB::commit();

            return response()->json([
                'success'    => true,
                'snap_token' => $snapToken,
                'order_id'   => $orderId,
                'payment_id' => $pembayaran->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment creation failed:', [
                'message'      => $e->getMessage(),
                'trace'        => $e->getTraceAsString(),
                'pemesanan_id' => $pemesananId,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pembayaran: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function handleFinish(Request $request)
    {
        Log::info('Payment finish callback received', $request->all());

        // Redirect ke halaman sukses
        return redirect()->route('pemesanan.index')
            ->with('success', 'Pembayaran telah selesai!');
    }

    public function handleError(Request $request)
    {
        Log::error('Payment error callback received', $request->all());

        return redirect()->route('pemesanan.index')
            ->with('error', 'Terjadi kesalahan dalam pembayaran.');
    }

    public function handlePending(Request $request)
    {
        Log::info('Payment pending callback received', $request->all());

        return redirect()->route('pemesanan.index')
            ->with('warning', 'Pembayaran sedang dalam proses.');
    }

    public function handleNotification(Request $request)
    {
        DB::beginTransaction();
        try {
            // Log the raw request data first
            Log::info('Raw notification data received:', $request->all());

            $notification = new \Midtrans\Notification();

            Log::info('Notification processed:', [
                'order_id'           => $notification->order_id,
                'transaction_status' => $notification->transaction_status,
                'fraud_status'       => $notification->fraud_status,
                'payment_type'       => $notification->payment_type,
                'status_code'        => $notification->status_code,
            ]);

            $orderId           = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus       = $notification->fraud_status;

            // Get payment data with detailed logging
            $pembayaran = Pembayaran::where('order_id', $orderId)->lockForUpdate()->first();

            if (! $pembayaran) {
                Log::error("Payment not found for order_id: {$orderId}");
                throw new \Exception("Payment with order_id {$orderId} not found");
            }

            Log::info('Found payment record:', [
                'payment_id'     => $pembayaran->id,
                'current_status' => $pembayaran->status,
            ]);

            // Update payment status with logging
            $newStatus = $this->getPaymentStatus($transactionStatus, $fraudStatus);
            Log::info('Updating payment status:', [
                'old_status'         => $pembayaran->status,
                'new_status'         => $newStatus,
                'transaction_status' => $transactionStatus,
                'fraud_status'       => $fraudStatus,
            ]);

            $pembayaran->status = $newStatus;
            $saved              = $pembayaran->save();

            if (! $saved) {
                Log::error('Failed to save payment status', [
                    'payment_id' => $pembayaran->id,
                    'new_status' => $newStatus,
                ]);
                throw new \Exception('Failed to save payment status');
            }

            // Update order status if payment is successful
            if ($newStatus === 'sudah_bayar') {
                $pemesanan = $pembayaran->pemesanan()->lockForUpdate()->first();
                if ($pemesanan) {
                    $pemesanan->status = 'selesai';
                    $saved             = $pemesanan->save();

                    if (! $saved) {
                        Log::error('Failed to update pemesanan status', [
                            'pemesanan_id' => $pemesanan->id,
                        ]);
                        throw new \Exception('Failed to update pemesanan status');
                    }

                    Log::info('Updated pemesanan status:', [
                        'pemesanan_id' => $pemesanan->id,
                        'new_status'   => 'selesai',
                    ]);
                }
            }

            DB::commit();
            Log::info("Payment status updated successfully", [
                'order_id'   => $orderId,
                'new_status' => $newStatus,
            ]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Notification handling failed:', [
                'message'      => $e->getMessage(),
                'trace'        => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function getPaymentStatus($transactionStatus, $fraudStatus)
    {
        if ($transactionStatus == 'capture') {
            return $fraudStatus == 'accept' ? 'sudah_bayar' : 'gagal';
        } else if ($transactionStatus == 'settlement') {
            return 'sudah_bayar';
        } else if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            return 'gagal';
        } else if ($transactionStatus == 'pending') {
            return 'belum_bayar';
        }

        return 'gagal';
    }
}
