<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\User;
use App\Models\Wisata;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use ArielMejiaDev\LarapexCharts\LarapexChart;

class DashboardController extends Controller
{
    public function index()
    {
        // Get current year and month
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        // Get monthly booking statistics
        $monthlyStats = Pemesanan::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('COUNT(*) as total_pemesanan'),
            DB::raw('SUM(total_harga) as total_pendapatan'),
            DB::raw('SUM(jumlah_tiket) as total_tiket')
        )
        ->whereYear('created_at', $currentYear)
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get();

        // Get summary statistics
        $summary = [
            'total_users' => User::count(),
            'total_wisata' => Wisata::count(),
            'total_pemesanan' => Pemesanan::whereMonth('created_at', $currentMonth)->count(),
            'total_pendapatan' => Pemesanan::whereMonth('created_at', $currentMonth)->sum('total_harga'),
        ];

        // Popular destinations
        $popularWisata = Wisata::select('wisatas.*', DB::raw('COUNT(pemesanans.id) as total_bookings'))
            ->leftJoin('pemesanans', 'wisatas.id', '=', 'pemesanans.wisata_id')
            ->groupBy('wisatas.id')
            ->orderByDesc('total_bookings')
            ->limit(5)
            ->get();

        // Create monthly bookings chart
        $bookingsChart = (new LarapexChart)
            ->setTitle('Statistik Pemesanan Tiket Per Bulan ' . $currentYear)
            ->setSubtitle('Total pemesanan dan pendapatan per bulan')
            ->setType('bar')
            ->setXAxis($monthlyStats->pluck('bulan')->map(function($month) {
                return Carbon::create()->month($month)->format('F');
            })->toArray())
            ->setDataset([
                [
                    'name' => 'Total Pemesanan',
                    'data' => $monthlyStats->pluck('total_pemesanan')->toArray(),
                ],
                [
                    'name' => 'Total Pendapatan (Juta)',
                    'data' => $monthlyStats->pluck('total_pendapatan')
                        ->map(fn($value) => round($value / 1000000, 2))
                        ->toArray(),
                ]
            ]);

        // Create popular destinations chart
        $destinationsChart = (new LarapexChart)
            ->setTitle('Destinasi Wisata Terpopuler')
            ->setType('pie')
            ->setDataset($popularWisata->pluck('total_bookings')->toArray())
            ->setLabels($popularWisata->pluck('nama_wisata')->toArray());

        return view('admin.dashboard', compact(
            'bookingsChart',
            'destinationsChart',
            'summary',
            'popularWisata',
            'monthlyStats'
        ));
    }
}
