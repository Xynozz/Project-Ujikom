<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Tiket;
use App\Models\Wisata;
use App\Models\Ulasan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DetailWisataController extends Controller
{
    public function index($id)
    {
        $wisata = Wisata::findOrFail($id);
        $tiket  = Tiket::all();
        $ulasan = $wisata->ulasan()->with('user')->get();

        return view('user.detail_wisata', compact('wisata', 'tiket', 'ulasan'));
    }

    public function store(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'tanggal_pemesanan' => 'required|date',
            'jumlah_tiket' => 'required|integer|min:1',
        ]);

        // Ambil data wisata berdasarkan ID dari URL
        $wisata = Wisata::findOrFail($id);

        // Ambil tiket terkait wisata
        $tiket = Tiket::where('wisata_id', $wisata->id)->first();

        if (!$tiket) {
            return redirect()->back()->withErrors(['tiket' => 'Tiket tidak ditemukan untuk wisata ini.']);
        }

        // Hitung total harga
        $total_harga = $tiket->harga_tiket * $request->jumlah_tiket;

        // Simpan pemesanan
        $pemesanan = new Pemesanan();
        $pemesanan->user_id = Auth::id();
        $pemesanan->tiket_id = $tiket->id;
        $pemesanan->wisata_id = $wisata->id;
        $pemesanan->tanggal_pemesanan = $request->tanggal_pemesanan;
        $pemesanan->jumlah_tiket = $request->jumlah_tiket;
        $pemesanan->total_harga = $total_harga;
        $pemesanan->save();

        return redirect()->back()->with('success', 'Pemesanan berhasil!');
    }


}
