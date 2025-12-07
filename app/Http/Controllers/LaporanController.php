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
        // Filtered Data for Table/Chart
        $query = DB::table('detail_pesanan')
            ->join('pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
            ->join('produk', 'detail_pesanan.produk_id', '=', 'produk.id')
            ->leftJoin('produk_varian', 'detail_pesanan.produk_varian_id', '=', 'produk_varian.id')
            ->whereBetween(DB::raw('DATE(pesanan.created_at)'), [$startDate, $endDate])
            ->where('pesanan.status_pembayaran', 'LUNAS');

        $totalPeriode = $query->sum('detail_pesanan.subtotal_item');
        
        $laporan = $query->select(
                'detail_pesanan.id',
                'pesanan.created_at',
                'pesanan.nomor_antrian',
                'pesanan.nama_pelanggan',
                'pesanan.status_pembayaran',
                'produk.nama_produk',
                'produk_varian.nama_varian',
                'detail_pesanan.jumlah',
                'detail_pesanan.subtotal_item'
            )
            ->orderBy('pesanan.created_at', 'desc')
            ->paginate(10);

        // Product Sales Summary
        $soldProducts = DB::table('detail_pesanan')
            ->join('pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
            ->join('produk', 'detail_pesanan.produk_id', '=', 'produk.id')
            ->whereBetween(DB::raw('DATE(pesanan.created_at)'), [$startDate, $endDate])
            ->where('pesanan.status_pembayaran', 'LUNAS')
            ->select(
                'produk.nama_produk',
                DB::raw('SUM(detail_pesanan.jumlah) as total_qty'),
                DB::raw('SUM(detail_pesanan.subtotal_item) as total_revenue')
            )
            ->groupBy('produk.id', 'produk.nama_produk')
            ->orderByDesc('total_revenue')
            ->get();

        // Chart Data: Daily Revenue in Range
        $dailySales = Pesanan::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_bayar) as total')
        )
        ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
        ->where('status_pembayaran', 'LUNAS')
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return view('laporan.index', compact(
            'dailyRevenue', 
            'monthlyRevenue', 
            'yearlyRevenue', 
            'laporan', 
            'soldProducts',
            'startDate', 
            'endDate',
            'totalPeriode',
            'dailySales'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $laporan = DB::table('detail_pesanan')
            ->join('pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
            ->join('produk', 'detail_pesanan.produk_id', '=', 'produk.id')
            ->leftJoin('produk_varian', 'detail_pesanan.produk_varian_id', '=', 'produk_varian.id')
            ->whereBetween(DB::raw('DATE(pesanan.created_at)'), [$startDate, $endDate])
            ->where('pesanan.status_pembayaran', 'LUNAS')
            ->select(
                'pesanan.created_at',
                'pesanan.created_at',
                'pesanan.nomor_antrian',
                'pesanan.nama_pelanggan',
                'produk.nama_produk',
                'produk_varian.nama_varian',
                'detail_pesanan.jumlah',
                'detail_pesanan.subtotal_item'
            )
            ->orderBy('pesanan.created_at', 'desc')
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
            fputcsv($file, ['Tanggal', 'Jam', 'No. Antrian', 'Pelanggan', 'Produk', 'Varian', 'Jumlah', 'Subtotal']);

            foreach ($laporan as $row) {
                fputcsv($file, [
                    Carbon::parse($row->created_at)->format('d/m/Y'),
                    Carbon::parse($row->created_at)->format('H:i'),
                    $row->nomor_antrian,
                    $row->nama_pelanggan ?: 'Umum',
                    $row->nama_produk,
                    $row->nama_varian ?: '-',
                    $row->jumlah,
                    $row->subtotal_item
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroy($id)
    {
        // Delete detail_pesanan directly
        DB::table('detail_pesanan')->where('id', $id)->delete();
        
        return redirect()->back()->with('success', 'Item transaksi berhasil dihapus.');
    }
}
