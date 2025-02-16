<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ulasan;
use App\Models\User;
use App\Models\Wisata;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $ulasan = Ulasan::all();
        $tanggal = Carbon::now()->setTimezone('Asia/Jakarta')->format('d-m-Y');

        return view('admin.ulasan.index', compact('ulasan', 'tanggal'));
    }

    public function create()
    {
        $ulasan = Ulasan::all();
        $user = User::all();
        $wisata = Wisata::all();

        return view('admin.ulasan.create', compact('ulasan', 'user', 'wisata'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'wisata_id' => 'required',
            'ulasan' => 'required',
            'rating' => 'required',
        ]);

        $ulasan = new Ulasan();
        $ulasan->user_id = $request->user_id;
        $ulasan->wisata_id = $request->wisata_id;
        $ulasan->ulasan = $request->ulasan;
        $ulasan->rating = $request->rating;
        $ulasan->tanggal_ulasan = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
        $ulasan->save();

        return redirect()->route('ulasan.index')->with('success', 'Ulasan berhasil ditambahkan');
    }

    public function edit(Request $request, $id){
        $ulasan = Ulasan::findOrFail($id);
        $user = User::all();
        $wisata = Wisata::all();

        return view('admin.ulasan.edit', compact('ulasan', 'user', 'wisata'));
    }

    public function update(Request $request, $id){
        $request->validate([
            'user_id' => 'required',
            'wisata_id' => 'required',
            'ulasan' => 'required',
            'rating' => 'required',
        ]);

        $ulasan = Ulasan::findOrFail($id);
        $ulasan->user_id = $request->user_id;
        $ulasan->wisata_id = $request->wisata_id;
        $ulasan->ulasan = $request->ulasan;
        $ulasan->rating = $request->rating;
        $ulasan->tanggal_ulasan = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d');
        $ulasan->save();

        return redirect()->route('ulasan.index')->with('success', 'Ulasan berhasil diupdate');
    }

    public function destroy($id){
        $ulasan = Ulasan::findOrFail($id);
        $ulasan->delete();

        return redirect()->route('ulasan.index')->with('success', 'Ulasan berhasil dihapus');
    }
}
