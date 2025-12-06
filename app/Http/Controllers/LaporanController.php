<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Default: Bulan Ini
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Ringkasan
        $dailyRevenue = Pesanan::whereDate('created_at', Carbon::today())
            ->where('status_pembayaran', 'LUNAS')
            ->sum('total_bayar');

        $monthlyRevenue = Pesanan::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->where('status_pembayaran', 'LUNAS')
            ->sum('total_bayar');

        $yearlyRevenue = Pesanan::whereYear('created_at', Carbon::now()->year)
            ->where('status_pembayaran', 'LUNAS')
            ->sum('total_bayar');

        // Filtered Data for Table/Chart
        $laporan = Pesanan::whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->where('status_pembayaran', 'LUNAS')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalPeriode = $laporan->sum('total_bayar');

        return view('laporan.index', compact(
            'dailyRevenue', 
            'monthlyRevenue', 
            'yearlyRevenue', 
            'laporan', 
            'startDate', 
            'endDate',
            'totalPeriode'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $laporan = Pesanan::whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->where('status_pembayaran', 'LUNAS')
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = "Laporan_Keuangan_" . $startDate . "_sd_" . $endDate . ".csv";

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=\"$filename\"",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($laporan) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal', 'No. Antrian', 'Pelanggan', 'Status', 'Total Bayar']);

            foreach ($laporan as $row) {
                fputcsv($file, [
                    $row->created_at->format('d/m/Y H:i'),
                    $row->nomor_antrian,
                    $row->nama_pelanggan ?: 'Umum',
                    $row->status_pembayaran,
                    $row->total_bayar
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
