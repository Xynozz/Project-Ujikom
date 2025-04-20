<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ulasan;
use App\Models\User;
use App\Models\Wisata;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $ulasan->save();

        return redirect()->route('ulasan.index')->with('success', 'Ulasan berhasil ditambahkan');
    }

    public function store1(Request $request)
    {

        $request->validate([
            'wisata_id' => 'required|exists:wisatas,id',
            'ulasan' => 'required|string',
            'rating' => 'required|integer|min:1|max:5', // Validasi rating antara 1 dan 5
        ]);

        // Simpan data ulasan dan rating ke database
        Ulasan::create([
            'wisata_id' => $request->wisata_id,
            'user_id' => Auth::id(),
            'rating' => $request->rating, // Pastikan ini ada
            'ulasan' => $request->ulasan,
        ]);

        return back()->with('success', 'Ulasan berhasil dikirim!');
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
        $ulasan->save();

        return redirect()->route('ulasan.index')->with('success', 'Ulasan berhasil diupdate');
    }

    public function destroy($id){
        $ulasan = Ulasan::findOrFail($id);
        $ulasan->delete();

        return redirect()->route('ulasan.index')->with('success', 'Ulasan berhasil dihapus');
    }
}
