<?php

namespace App\Http\Controllers;

use App\Models\Detail_pemesanan;
use App\Models\DetailPemesanan;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Str;
use Carbon\Carbon;

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
                Log::error('Order ID is empty');
                return response()->json(['status' => 'error', 'message' => 'Order ID is empty'], 200);
            }

            // Ambil ID pemesanan dari order_id (format: ORDER-<timestamp>-<pemesanan_id>)
            $orderParts = explode('-', $orderId);
            if (count($orderParts) < 3 || !is_numeric($orderParts[2])) {
                Log::error('Invalid order ID format: ' . $orderId);
                return response()->json(['status' => 'error', 'message' => 'Invalid order format'], 200);
            }

            $pemesananId = (int) $orderParts[2];

            DB::beginTransaction();
            try {
                // Ambil data pemesanan dan kunci selama update
                $pemesanan = Pemesanan::lockForUpdate()->find($pemesananId);
                if (!$pemesanan) {
                    Log::error("Pemesanan dengan ID $pemesananId tidak ditemukan!");
                    return response()->json(['status' => 'error', 'message' => 'Pemesanan tidak ditemukan'], 404);
                }

                Log::info('Current pemesanan status: ' . $pemesanan->status);

                // Tentukan status berdasarkan transaction_status
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

                Log::info("Updating status to: $newStatus");

                // Simpan status baru pada pemesanan
                $pemesanan->status = $newStatus;
                $pemesanan->save();

                // Simpan pembayaran
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

                Log::info("Pembayaran ditemukan: ", ['pembayaran_id' => $pembayaran->id]);

                // Jika pembayaran sukses, buat detail pemesanan (tiket)
                if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
                    $existingDetail = Detail_pemesanan::where('pemesanan_id', $pemesananId)->first();

                    do {
                        $barcode = 'TIKET-' . $pemesananId . '-' . Str::random(6);
                    } while (Detail_pemesanan::where('barcode', $barcode)->exists());


                    if (!$existingDetail) {
                        Detail_pemesanan::create([
                            'pemesanan_id'  => $pemesananId,
                            'pembayaran_id' => $pembayaran->id, // Pastikan pembayaran_id tidak kosong
                            'wisata_id'     => $pemesanan->wisata_id ?? null, // Pastikan ada wisata_id
                            'tiket_id'      => $pemesanan->tiket_id ?? null, // Pastikan ada tiket_id
                            'barcode'       => $barcode,
                            'expired_at'    => Carbon::now()->addDays(1),
                            'status'        => 'Unexpired',
                        ]);

                        Log::info("Detail pemesanan berhasil dibuat untuk pemesanan ID: $pemesananId");
                    } else {
                        Log::warning("Detail pemesanan sudah ada untuk pemesanan ID: $pemesananId");
                    }
                }

                DB::commit();

                return response()->json([
                    'status'            => 'success',
                    'message'           => 'Payment callback processed successfully',
                    'pemesanan_status'  => $pemesanan->status,
                    'pembayaran_status' => $pembayaran->status,
                ], 200);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Transaction Error: ' . $e->getMessage());
                return response()->json(['status' => 'error', 'message' => 'Gagal memproses pembayaran'], 500);
            }

        } catch (\Exception $e) {
            Log::error('Callback Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Payment callback processing failed'], 500);
        }
    }
}
