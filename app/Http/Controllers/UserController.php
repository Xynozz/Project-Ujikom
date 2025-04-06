<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wisata;
use App\Models\Tiket;
use App\Models\Kategori;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function index()
    {
        $wisata = Wisata::all();
        $tiket = Tiket::all();
        $kategori = Kategori::all();

        $newWisata = Wisata::where('created_at', '>=', now()->subDays(30))
        ->orderBy('created_at', 'desc')
        ->get()
        ->map(function ($item) {
            $item->thumbnail = url('storage/' . $item->thumbnail);

            // Tambahkan tiket terkait ke dalam objek wisata
            $item->tiket = Tiket::where('wisata_id', $item->id)->get();

            return $item;
        });

        return view('welcome', compact('wisata', 'tiket', 'kategori', 'newWisata'));
    }


    public function index2(Request $request)
    {
        Log::info("🔍 Request API dari Flutter: ", $request->headers->all());

        $wisata = Wisata::all()->map(function ($item) {
            $item->thumbnail = url('storage/' . $item->thumbnail);
            return $item;
        });

        $tiket = Tiket::all();

        return response()->json([
            'success' => true,
            'message' => 'Daftar Destinasi berhasil diambil.',
            'data' => $wisata, $tiket
        ], 200);
    }

    public function detailWisata($id)
    {
        $wisata = Wisata::with('tiket')->findOrFail($id);

        return view('user.detail_wisata', compact('wisata'));
    }
}
