<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tiket;
use App\Models\Wisata;
use Illuminate\Http\Request;

class TiketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tiket = Tiket::all();
        $wisata = Wisata::all();

        return view('admin.tiket.index', compact('tiket', 'wisata'));
    }

    public function create()
    {
        $tiket = Tiket::all();
        $wisata = Wisata::all();

        return view('admin.tiket.create', compact('tiket', 'wisata'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'wisata_id' => 'required',
            'harga_tiket' => 'required',
        ]);

        $tiket = new Tiket();
        $tiket->wisata_id = $request->wisata_id;
        $tiket->harga_tiket = $request->harga_tiket;
        $tiket->save();

        return redirect()->route('tiket.index')->with('success', 'Tiket berhasil ditambahkan');
    }

    public function edit($id)
    {
        $tiket = Tiket::findOrFail($id);
        $wisata = Wisata::all();

        return view('admin.tiket.edit', compact('tiket', 'wisata'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'wisata_id' => 'required',
            'harga_tiket' => 'required',
        ]);

        $tiket = Tiket::findOrFail($id);
        $tiket->wisata_id = $request->wisata_id;
        $tiket->harga_tiket = $request->harga_tiket;
        $tiket->save();

        return redirect()->route('tiket.index')->with('success', 'Tiket berhasil diperbarui');
    }

    public function destroy($id)
    {
        $tiket = Tiket::findOrFail($id);
        $tiket->delete();
        return redirect()->route('tiket.index')->with('success', 'Tiket berhasil dihapus');
    }

}
