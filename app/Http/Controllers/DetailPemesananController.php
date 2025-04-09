<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Detail_pemesanan;
use App\Models\Pemesanan;
use App\Models\Tiket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DetailPemesananController extends Controller
{
    public function index()
    {
        $detailPemesanan = Detail_pemesanan::all();
        $pemesanan       = Pemesanan::all();
        $tiket           = Tiket::all();
        $user            = User::all();
        return view('admin.detail_pemesanan.index', compact('detailPemesanan', 'pemesanan', 'tiket', 'user'));
    }

    public function activateQrFromUrl(Request $request)
    {
        $url = $request->input('url');

        if (! $url) {
            return redirect()->back()->with('qr_error', 'URL QR tidak boleh kosong.');
        }

        // Ambil nama file dari url, lalu cocokkan dengan qr_path
        $filename = basename($url);
        $detail   = Detail_Pemesanan::where('qr_path', 'like', "%$filename")->first();

        if (! $detail) {
            return redirect()->back()->with('qr_error', 'Tiket tidak ditemukan!');
        }

        if ($detail->status === 'Activate') {
            return redirect()->back()->with('qr_success', 'Tiket sudah aktif!');
        }

        // Set status ke "Activate" dan expired_at ke waktu sekarang
        $detail->update([
            'status'     => 'Activate',
            'expired_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('qr_success', 'Tiket berhasil diaktivasi, Tiket Expired!');
    }

}
