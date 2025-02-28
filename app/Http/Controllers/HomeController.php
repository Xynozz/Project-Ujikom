<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Wisata;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $kategori = Kategori::all();
        $wisata = Wisata::all();

        return view('user/home', compact('kategori', 'wisata'));
    }
}
