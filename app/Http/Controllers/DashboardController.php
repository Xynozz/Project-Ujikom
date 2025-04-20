<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Pesanan
        $totalPesanan = Pemesanan::count();

        // Pendapatan (misalnya dari status 'Lunas')
        $totalPendapatan = Pemesanan::where('status', 'selesai')->sum('total_harga');

        // Jumlah Pengunjung (jumlah user atau pelanggan aktif)
        $jumlahPengunjung = User::where('is_admin', 0)->count();

        // Rating rata-rata dari ulasan
        $ratingRataRata = DB::table('ulasans')->avg('rating');
        $ratingRataRata = number_format($ratingRataRata, 1);

        // Data pesanan terbaru
        $pesananTerbaru = Pemesanan::latest()->take(5)->with(['user', 'wisata'])->get();

        $startDate = Carbon::now()->subDays(6); // 7 hari terakhir termasuk hari ini
        $salesData = Pemesanan::selectRaw('DATE(created_at) as tanggal, COUNT(*) as jumlah, SUM(total_harga) as total')
            ->whereBetween('created_at', [$startDate, Carbon::now()])
            ->groupByRaw('DATE(created_at)')
            ->orderBy('tanggal')
            ->get();

        // Format untuk Chart.js
        $labels      = $salesData->pluck('tanggal')->map(fn($d) => Carbon::parse($d)->format('d M'));
        $jumlahTiket = $salesData->pluck('jumlah');
        $pendapatan  = $salesData->pluck('total')->map(fn($total) => round($total / 1000)); // misal dalam ribuan

        // Ambil 5 destinasi terpopuler berdasarkan jumlah kunjungan
        $data = DB::table('pemesanans')
            ->join('wisatas', 'pemesanans.wisata_id', '=', 'wisatas.id')
            ->select('wisatas.nama_wisata', DB::raw('COUNT(*) as total'))
            ->groupBy('wisatas.nama_wisata')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPesanan',
            'totalPendapatan',
            'jumlahPengunjung',
            'ratingRataRata',
            'pesananTerbaru',
            'data',
            'labels',
            'jumlahTiket',
            'pendapatan'
            // 'destinasiPopuler'
        ));

    }
}
