<?php

namespace App\Services;

use App\Models\Pemesanan;
use App\Models\Pembayaran;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createTransaction(Pemesanan $pemesanan)
    {
        $user = $pemesanan->user;

        $params = [
            'transaction_details' => [
                'order_id' => $pemesanan->order_number,
                'gross_amount' => (int) $pemesanan->total_harga,
            ],
            'customer_details' => [
                'first_name' => $user->nama_lengkap,
                'email' => $user->email,
                'phone' => $user->no_hp ?? '',
            ],
            'item_details' => [
                [
                    'id' => $pemesanan->tiket_id,
                    'price' => (int) $pemesanan->tiket->harga,
                    'quantity' => $pemesanan->jumlah_tiket,
                    'name' => 'Tiket ' . $pemesanan->wisata->nama,
                ]
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            // Simpan token ke pembayaran
            $pembayaran = Pembayaran::create([
                'pemesanan_id' => $pemesanan->id,
                'order_id' => $pemesanan->order_number,
                'jumlah_pembayaran' => $pemesanan->total_harga,
                'status' => 'belum_bayar',
                'snap_token' => $snapToken
            ]);

            return $snapToken;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function notificationHandler($notification)
    {
        $transactionStatus = $notification->transaction_status;
        $paymentType = $notification->payment_type;
        $orderId = $notification->order_id;
        $fraudStatus = $notification->fraud_status ?? null;

        $pemesanan = Pemesanan::where('order_number', $orderId)->first();

        if ($pemesanan) {
            $pembayaran = $pemesanan->pembayaran;

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $pembayaran->status = 'pending';
                } else if ($fraudStatus == 'accept') {
                    $pembayaran->status = 'sudah_bayar';
                    $pemesanan->status = 'selesai';
                }
            } else if ($transactionStatus == 'settlement') {
                $pembayaran->status = 'sudah_bayar';
                $pemesanan->status = 'selesai';
            } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
                $pembayaran->status = 'gagal';
                $pemesanan->status = 'batal';
            } else if ($transactionStatus == 'pending') {
                $pembayaran->status = 'pending';
            }

            $pembayaran->metode_pembayaran = $paymentType;
            $pembayaran->data_pembayaran = json_decode(json_encode($notification), true);
            $pembayaran->tanggal_pembayaran = now();
            $pembayaran->save();
            $pemesanan->save();

            return true;
        }

        return false;
    }
}