<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wisata;
use App\Models\Tiket;
use App\Models\Kategori;

class DetailWisataController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();
        $wisata = Wisata::all();
        $tiket = Tiket::all();

        return view('user.detail_wisata', compact('kategori', 'wisata', 'tiket'));
    }
}
