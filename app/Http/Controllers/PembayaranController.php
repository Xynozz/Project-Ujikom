<?php
namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Carbon\Carbon;

use App\Models\Pembayaran;

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

    public function index($id)
    {
        $pemesanan = Pemesanan::with('tiket', 'tiket.wisata')->findOrFail($id);


        return view('user.detail_pembayaran',[
            'tanggal_kunjungan' => Carbon::parse($pemesanan->tanggal_kunjungan),
            'pemesanan'         => $pemesanan,
        ]);
    }

    public function success($order_id)
    {
        $pembayaran = Pembayaran::where('order_id', $order_id)->firstOrFail();
        $pemesanan  = Pemesanan::with('tiket', 'tiket.wisata')->where('id', $pembayaran->pemesanan_id)->firstOrFail();

        return view('user.success', compact('pembayaran', 'pemesanan'));
    }

}
