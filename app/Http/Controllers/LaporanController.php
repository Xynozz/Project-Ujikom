<?php
namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanController extends Controller
{
    public function userReport()
    {
        $users = User::withCount('pemesanan')
            ->withSum('pemesanan', 'total_harga')
            ->get()
            ->map(function ($user) {
                $user->total_pembayaran = $user->pemesanan_sum_total_harga ?? 0;
                return $user;
            });

        return view('admin.laporan.user', compact('users'));
    }

    public function exportUserPDF()
    {
        $users = User::withCount('pemesanan')
            ->withSum('pemesanan', 'total_harga')
            ->get()
            ->map(function ($user) {
                $user->total_pembayaran = $user->pemesanan_sum_total_harga ?? 0;
                return $user;
            });

        $pdf = PDF::loadView('admin.laporan.user-pdf', compact('users'));
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-user-' . date('Y-m-d') . '.pdf');
    }

    public function pemesananReport(Request $request)
    {
        // Ambil data dengan filter tanggal
        $pemesanan = Pemesanan::with(['user', 'wisata', 'tiket'])
            ->when($request->date_from, function ($q) use ($request) {
                return $q->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->date_to, function ($q) use ($request) {
                return $q->whereDate('created_at', '<=', $request->date_to);
            })
            ->latest()
            ->get();

        // Kirim data ke view
        return view('admin.laporan.pemesanan', compact('pemesanan'));
    }

    public function exportPemesananPDF(Request $request)
    {
        // Ambil data dengan filter tanggal
        $pemesanan = Pemesanan::with(['user', 'wisata', 'tiket'])
            ->when($request->date_from, function ($q) use ($request) {
                return $q->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->date_to, function ($q) use ($request) {
                return $q->whereDate('created_at', '<=', $request->date_to);
            })
            ->latest()
            ->get();

        // Generate PDF
        $pdf = PDF::loadView('admin.laporan.pemesanan-pdf', compact('pemesanan'));
        $pdf->setPaper('a4', 'landscape');

        $fileName = 'laporan-pemesanan-' . date('Y-m-d') . '.pdf';

        return response()->streamDownload(
            function () use ($pdf) {
                echo $pdf->output();
            },
            $fileName,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]
        );
    }

    // public function pendapatanReport(Request $request)
    // {
    //     $pendapatan = Pemesanan::select(
    //         DB::raw('DATE(created_at) as date'),
    //         DB::raw('COUNT(*) as total_orders'),
    //         DB::raw('SUM(total_harga) as total_income')
    //     )
    //         ->when($request->date_from, function ($q) use ($request) {
    //             return $q->whereDate('created_at', '>=', $request->date_from);
    //         })
    //         ->when($request->date_to, function ($q) use ($request) {
    //             return $q->whereDate('created_at', '<=', $request->date_to);
    //         })
    //         ->where('status', 'success')
    //         ->groupBy('date')
    //         ->orderBy('date', 'desc')
    //         ->get();

    //     return view('admin.laporan.pendapatan', compact('pendapatan'));
    // }
    public function exportExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'LAPORAN DATA USER');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->getFont()->setBold(true);

        // Set column headers
        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Username');
        $sheet->setCellValue('C3', 'Email');
        $sheet->setCellValue('D3', 'No HP');
        $sheet->setCellValue('E3', 'Total Pemesanan');
        $sheet->setCellValue('F3', 'Total Pembayaran');
        $sheet->setCellValue('G3', 'Status');

        // Style header row
        $headerStyle = [
            'font'      => ['bold' => true],
            'alignment' => ['horizontal' => 'center'],
            'borders'   => [
                'allBorders' => ['borderStyle' => 'thin'],
            ],
            'fill'      => [
                'fillType'   => 'solid',
                'startColor' => ['rgb' => 'E9ECEF'],
            ],
        ];
        $sheet->getStyle('A3:G3')->applyFromArray($headerStyle);

        // Get data
        $users = User::withCount('pemesanan')
            ->withSum('pemesanan', 'total_harga')
            ->get()
            ->map(function ($user) {
                $user->total_pembayaran = $user->pemesanan_sum_total_harga ?? 0;
                return $user;
            });

        // Fill data
        $row = 4;
        foreach ($users as $key => $user) {
            $sheet->setCellValue('A' . $row, $key + 1);
            $sheet->setCellValue('B' . $row, $user->username);
            $sheet->setCellValue('C' . $row, $user->email);
            $sheet->setCellValue('D' . $row, $user->no_hp);
            $sheet->setCellValue('E' . $row, $user->pemesanan_count);
            $sheet->setCellValue('F' . $row, 'Rp ' . number_format($user->total_pembayaran, 0, ',', '.'));
            $sheet->setCellValue('G' . $row, $user->status);
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set borders for data
        $sheet->getStyle('A3:G' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle('thin');

        // Create writer
        $writer = new Xlsx($spreadsheet);

        // Prepare response
        $fileName = 'laporan-user-' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        // Save file to output
        $writer->save('php://output');
        exit;
    }

    public function exportPemesananExcel(Request $request)
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        // Set header
        $sheet->setCellValue('A1', 'LAPORAN PEMESANAN TIKET');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        // Set period if exists
        if ($request->date_from || $request->date_to) {
            $period = 'Periode: ' . ($request->date_from ? date('d/m/Y', strtotime($request->date_from)) : '')
                . ($request->date_from && $request->date_to ? ' - ' : '')
                . ($request->date_to ? date('d/m/Y', strtotime($request->date_to)) : '');
            $sheet->setCellValue('A2', $period);
            $sheet->mergeCells('A2:H2');
            $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');
        }

        // Set column headers
        $headers = ['No', 'Tanggal', 'Kode', 'Pemesan', 'Wisata', 'Status', 'Jumlah Tiket', 'Total'];
        $col     = 'A';
        $row     = 4;
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $col++;
        }

        // Style header row
        $headerStyle = [
            'font'      => ['bold' => true],
            'alignment' => [
                'horizontal' => 'center',
                'vertical'   => 'center',
            ],
            'borders'   => [
                'allBorders' => ['borderStyle' => 'thin'],
            ],
            'fill'      => [
                'fillType'   => 'solid',
                'startColor' => ['rgb' => 'E9ECEF'],
            ],
        ];
        $sheet->getStyle('A4:H4')->applyFromArray($headerStyle);

        // Get data
        $pemesanan = Pemesanan::with(['user', 'wisata', 'tiket'])
            ->when($request->date_from, function ($q) use ($request) {
                return $q->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->date_to, function ($q) use ($request) {
                return $q->whereDate('created_at', '<=', $request->date_to);
            })
            ->latest()
            ->get();

        // Fill data
        $row   = 5;
        $total = 0;
        foreach ($pemesanan as $key => $item) {
            $sheet->setCellValue('A' . $row, $key + 1);
            $sheet->setCellValue('B' . $row, $item->created_at->format('d/m/Y'));
            $sheet->setCellValue('C' . $row, $item->tiket->kode_tiket);
            $sheet->setCellValue('D' . $row, $item->user->username);
            $sheet->setCellValue('E' . $row, $item->wisata->nama_wisata);
            $sheet->setCellValue('F' . $row, $item->status);
            $sheet->setCellValue('G' . $row, $item->jumlah_tiket);
            $sheet->setCellValue('H' . $row, $item->total_harga);

            // Update column alignments
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('H' . $row)->getAlignment()->setHorizontal('center');

            $total += $item->total_harga;
            $row++;
        }

        // Add total row with center alignment
        $sheet->setCellValue('G' . $row, 'Total');
        $sheet->setCellValue('H' . $row, $total);
        $sheet->getStyle('G' . $row)->getFont()->setBold(true);
        $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal('center'); // Add center alignment
        $sheet->getStyle('H' . $row)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('H' . $row)->getAlignment()->setHorizontal('center'); // Add center alignment for total amount

        // Auto size columns
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Set borders for data
        $sheet->getStyle('A4:H' . ($row))->getBorders()->getAllBorders()->setBorderStyle('thin');

        // Create writer
        $writer = new Xlsx($spreadsheet);

        // Prepare response
        $fileName = 'laporan-pemesanan-' . date('Y-m-d') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        // Save file to output
        $writer->save('php://output');
        exit;
    }
}
