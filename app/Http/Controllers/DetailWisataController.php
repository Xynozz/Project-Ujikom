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

}
