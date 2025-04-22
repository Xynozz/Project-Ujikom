<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use App\Models\Tiket;
use Illuminate\Http\Request;

class DestinasiController extends Controller
{
    public function index()
    {
        $wisatas = Wisata::all();
        $tiket = Tiket::all();

        foreach ($wisatas as $wisata) {
            $wisata->latitude;
            $wisata->longitude;
        }

        return view('user.destinasi', compact('wisatas', 'tiket', 'wisata'));
    }
}
