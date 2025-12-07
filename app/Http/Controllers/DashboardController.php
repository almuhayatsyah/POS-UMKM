<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\BahanBaku;
use App\Models\Produk;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // 1. Ringkasan Hari Ini
        $todayRevenue = Pesanan::whereDate('created_at', $today)
            ->where('status_pembayaran', 'LUNAS')
            ->sum('total_bayar');

        $todayOrders = Pesanan::whereDate('created_at', $today)->count();

        // 2. Status Semua Bahan Baku (Urutkan: Stok Menipis di atas)
        $allStockItems = BahanBaku::orderByRaw('stok_saat_ini <= stok_minimum DESC')
            ->orderBy('stok_saat_ini', 'asc')
            ->get();

        // 3. Grafik Penjualan 7 Hari Terakhir
        $salesChart = Pesanan::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_bayar) as total')
        )
        ->where('created_at', '>=', Carbon::now()->subDays(7))
        ->where('status_pembayaran', 'LUNAS')
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();

        // 4. Pesanan Terbaru
        $recentOrders = Pesanan::with('detailPesanan.produk')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 5. Produk Terlaris (Top 5)
        $topProducts = DB::table('detail_pesanan')
            ->join('produk', 'detail_pesanan.produk_id', '=', 'produk.id')
            ->select('produk.nama_produk', 'produk.url_gambar', DB::raw('SUM(detail_pesanan.jumlah) as total_sold'))
            ->groupBy('produk.id', 'produk.nama_produk', 'produk.url_gambar')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // 6. Penjualan per Kategori (Pie Chart)
        $categorySales = DB::table('detail_pesanan')
            ->join('produk', 'detail_pesanan.produk_id', '=', 'produk.id')
            ->join('pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
            ->where('pesanan.status_pembayaran', 'LUNAS')
            ->select('produk.kategori', DB::raw('SUM(detail_pesanan.subtotal_item) as total_revenue'))
            ->groupBy('produk.kategori')
            ->get();

        return view('dashboard.index', compact(
            'todayRevenue', 
            'todayOrders', 
            'allStockItems', 
            'salesChart', 
            'recentOrders',
            'topProducts',
            'categorySales'
        ));
    }
}
