<?php
namespace App\Http\Controllers;

use App\Models\Detail_pemesanan;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

// Tambahkan untuk enkripsi

class MidtransController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function createTransaction(Request $request)
    {
        try {
            $pemesanan = Pemesanan::with(['user', 'wisata', 'tiket'])->findOrFail($request->pemesanan_id);

            $transaction_details = [
                'transaction_details' => [
                    'order_id'     => 'ORDER-' . time() . '-' . $pemesanan->id,
                    'gross_amount' => (int) $pemesanan->total_harga,
                ],
                'customer_details'    => [
                    'first_name' => $pemesanan->user->username,
                    'email'      => $pemesanan->user->email,
                ],
                'item_details'        => [
                    [
                        'id'       => $pemesanan->tiket->id,
                        'price'    => (int) $pemesanan->tiket->harga_tiket,
                        'quantity' => (int) $pemesanan->jumlah_tiket,
                        'name'     => $pemesanan->tiket->kode_tiket,
                    ],
                ],
            ];

            $snapToken = Snap::getSnapToken($transaction_details);

            return response()->json([
                'snap_token' => $snapToken,
                'message'    => 'Success',
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function paymentCallback(Request $request)
    {
        Log::info('=== MIDTRANS CALLBACK RECEIVED ===');
        Log::info('Request Data:', $request->all());

        try {
            $orderId           = $request->order_id ?? '';
            $transactionStatus = $request->transaction_status ?? '';
            $transactionTime   = $request->transaction_time ?? now();

            if (empty($orderId)) {
                return response()->json(['status' => 'error', 'message' => 'Order ID kosong'], 200);
            }

            $orderParts = explode('-', $orderId);
            if (count($orderParts) < 3 || ! is_numeric($orderParts[2])) {
                return response()->json(['status' => 'error', 'message' => 'Format order ID tidak valid'], 200);
            }

            $pemesananId = (int) $orderParts[2];

            DB::beginTransaction();

            $pemesanan = Pemesanan::lockForUpdate()->find($pemesananId);
            if (! $pemesanan) {
                return response()->json(['status' => 'error', 'message' => 'Pemesanan tidak ditemukan'], 404);
            }

            $statusPembayaran = match ($transactionStatus) {
                'capture', 'settlement' => 'sudah_bayar',
                'pending'               => 'pending',
                default                 => 'gagal',
            };

            $newStatus = match ($transactionStatus) {
                'capture', 'settlement' => 'selesai',
                'pending'               => 'proses',
                default                 => 'batal',
            };

            $pemesanan->status = $newStatus;
            $pemesanan->save();

            $pembayaran = Pembayaran::updateOrCreate(
                ['order_id' => $orderId],
                [
                    'pemesanan_id'       => $pemesananId,
                    'status'             => $statusPembayaran,
                    'metode_pembayaran'  => $request->payment_type ?? 'unknown',
                    'tanggal_pembayaran' => $transactionTime,
                    'updated_at'         => now(),
                ]
            );

            if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
                $existingDetail = Detail_pemesanan::where('pemesanan_id', $pemesananId)->first();

                do {
                    $qrCodeContent = 'TIKET-' . $pemesananId . '-' . strtoupper(Str::random(10));
                    $qrFileName = 'qr_code_' . $pemesananId . '_' . time() . '.png';
                    $qrPath = 'qr_codes/' . $qrFileName;
                } while (Detail_pemesanan::where('qr_code', $qrCodeContent)->exists());

                if (!$existingDetail) {
                    QrCode::format('png')->size(300)->generate($qrCodeContent, storage_path('app/public/' . $qrPath));

                    Detail_pemesanan::create([
                        'pemesanan_id'  => $pemesananId,
                        'pembayaran_id' => $pembayaran->id,
                        'wisata_id'     => $pemesanan->wisata_id ?? null,
                        'tiket_id'      => $pemesanan->tiket_id ?? null,
                        'qr_code'       => $qrCodeContent,
                        'qr_path'       => 'storage/' . $qrPath,
                        'expired_at'    => Carbon::now()->addDays(1),
                        'status'        => 'Unexpired',
                    ]);

                    Log::info("Detail pemesanan berhasil dibuat dengan QR: $qrCodeContent");
                } else {
                    Log::warning("Detail pemesanan sudah ada untuk pemesanan ID: $pemesananId");
                }
            }

            DB::commit();

            return response()->json([
                'status'            => 'success',
                'message'           => 'Callback berhasil',
                'pemesanan_status'  => $pemesanan->status,
                'pembayaran_status' => $pembayaran->status,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Callback gagal'], 500);
        }
    }

}
