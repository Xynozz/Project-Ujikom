<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\User;
use App\Models\Tiket;
use App\Models\Wisata;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $pemesanan = Pemesanan::all();
        $user = User::all();
        $tiket = Tiket::all();
        $tanggal = Carbon::now()->setTimezone('Asia/Jakarta')->format('d-m-Y');

        return view('admin.pemesanan.index', compact('pemesanan', 'user', 'tiket', 'tanggal'));
    }

    public function updateStatus(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'status' => 'required|string'
        ]);

        // Cari data berdasarkan ID
        $pemesanan = Pemesanan::findOrFail($id);

        // Update hanya field "status"
        $pemesanan->update([
            'status' => $request->status
        ]);

        return redirect()->route('pemesanan.index')->with('success', 'Status berhasil diubah!');

    }

    public function create()
    {
        $pemesanan = Pemesanan::all();
        $tiket = Tiket::all();
        $user = User::all();
        $wisata = Wisata::all();

        return view('admin.pemesanan.create', compact('pemesanan', 'user', 'wisata', 'tiket'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'tiket_id' => 'required',
            'wisata_id' => 'required',
            'jumlah_tiket' => 'required',
        ]);

        // Ambil harga tiket berdasarkan tiket_id
        $tiket = Tiket::find($request->tiket_id);
        if (!$tiket) {
            return redirect()->back()->withErrors(['tiket_id' => 'Tiket tidak ditemukan']);
        }

        // Hitung total harga
        $total_harga = $tiket->harga_tiket * $request->jumlah_tiket;

        $pemesanan = new Pemesanan();
        $pemesanan->user_id = $request->user_id;
        $pemesanan->tiket_id = $request->tiket_id;
        $pemesanan->wisata_id = $request->wisata_id;
        $pemesanan->tanggal_pemesanan = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
        $pemesanan->jumlah_tiket = $request->jumlah_tiket;
        $pemesanan->total_harga = $total_harga;
        $pemesanan->save();

        return redirect()->route('pemesanan.index')->with('success', 'Pemesanan berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        $tiket = Tiket::all();
        $user = User::all();
        $wisata = Wisata::all();

        return view('admin.pemesanan.edit', compact('pemesanan', 'user', 'wisata', 'tiket'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required',
            'tiket_id' => 'required',
            'wisata_id' => 'required',
            'jumlah_tiket' => 'required',
        ]);

        // Ambil harga tiket berdasarkan tiket_id
        $tiket = Tiket::find($request->tiket_id);
        if (!$tiket) {
            return redirect()->back()->withErrors(['tiket_id' => 'Tiket tidak ditemukan']);
        }

        // Hitung total harga
        $total_harga = $tiket->harga_tiket * $request->jumlah_tiket;

        $pemesanan = Pemesanan::findOrFail($id);
        $pemesanan->user_id = $request->user_id;
        $pemesanan->tiket_id = $request->tiket_id;
        $pemesanan->wisata_id = $request->wisata_id;
        $pemesanan->tanggal_pemesanan = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
        $pemesanan->jumlah_tiket = $request->jumlah_tiket;
        $pemesanan->total_harga = $total_harga;
        $pemesanan->save();

        return redirect()->route('pemesanan.index')->with('success', 'Pemesanan berhasil diupdate!');
    }

    public function destroy($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        $pemesanan->delete();

        return redirect()->route('pemesanan.index')->with('success', 'Pemesanan berhasil dihapus!');
    }
}
