@extends('layouts.app')

@section('content')
<div class="row">
    <!-- Welcome Card -->
    <div class="col-lg-8 mb-4 order-0">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-sm-7">
                    <div class="card-body">
                        <h5 class="card-title text-primary">Selamat Datang Admin! 🎉</h5>
                        <p class="mb-4">
                            Hari ini Anda telah menjual <span class="fw-bold">{{ $todayOrders }}</span> pesanan.
                            Cek stok bahan baku yang menipis di bawah ini.
                        </p>
                        <a href="{{ route('pos.index') }}" class="btn btn-sm btn-primary">Buka Kasir</a>
                    </div>
                </div>
                <div class="col-sm-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="140" alt="View Badge User" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="col-lg-4 col-md-4 order-1">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <span class="avatar-initial rounded bg-label-success"><i class="bx bx-dollar"></i></span>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Omset Hari Ini</span>
                        <h3 class="card-title mb-2">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <span class="avatar-initial rounded bg-label-info"><i class="bx bx-cart"></i></span>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Total Pesanan</span>
                        <h3 class="card-title text-nowrap mb-1">{{ $todayOrders }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <!-- Weekly Revenue Chart -->
    <div class="col-lg-8 col-md-12 mb-4">
        <div class="card h-100">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Pendapatan Minggu Ini 📈</h5>
            </div>
            <div class="card-body">
                <div id="weeklyRevenueChart"></div>
            </div>
        </div>
    </div>

    <!-- Category Sales Chart -->
    <div class="col-lg-4 col-md-12 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between pb-0">
                <h5 class="card-title m-0 me-2">Kategori Terlaris 🍰</h5>
            </div>
            <div class="card-body">
                <div id="categoryPieChart" style="min-height: 250px;"></div>
                <div class="mt-3 text-center small text-muted">
                    Proporsi berdasarkan total pendapatan.
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Stock Status -->
    <div class="col-md-6 col-lg-4 order-2 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0 me-2">Status Bahan Baku 📦</h5>
                <a href="{{ route('bahan-baku.index') }}" class="small">Lihat Semua</a>
            </div>
            <div class="card-body">
                <ul class="p-0 m-0" style="max-height: 300px; overflow-y: auto;">
                    @forelse($allStockItems as $item)
                    <li class="d-flex mb-4 pb-1 border-bottom">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded {{ $item->stok_saat_ini <= $item->stok_minimum ? 'bg-label-danger' : 'bg-label-success' }}">
                                <i class="bx bx-package"></i>
                            </span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <h6 class="mb-0">{{ $item->nama_bahan }}</h6>
                                <small class="text-muted">Min: {{ $item->stok_minimum }} {{ $item->satuan }}</small>
                            </div>
                            <div class="user-progress text-end">
                                <small class="fw-semibold d-block {{ $item->stok_saat_ini <= $item->stok_minimum ? 'text-danger' : 'text-success' }}">
                                    {{ $item->stok_saat_ini }} {{ $item->satuan }}
                                </small>
                                <small class="text-muted" style="font-size: 0.7rem;">
                                    {{ $item->stok_saat_ini <= $item->stok_minimum ? 'Menipis' : 'Aman' }}
                                </small>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="text-center text-muted">Belum ada data bahan baku.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Top Products -->
    <div class="col-md-6 col-lg-4 order-2 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0 me-2">Best Seller 🔥</h5>
                <a href="{{ route('produk.index') }}" class="small">Lihat Semua</a>
            </div>
            <div class="card-body">
                <ul class="p-0 m-0">
                    @forelse($topProducts as $product)
                    <li class="d-flex mb-4 pb-1">
                        <div class="avatar flex-shrink-0 me-3">
                            @if($product->url_gambar)
                                <img src="{{ asset('storage/' . $product->url_gambar) }}" alt="{{ $product->nama_produk }}" class="rounded" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-trophy"></i></span>
                            @endif
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <h6 class="mb-0">{{ $product->nama_produk }}</h6>
                            </div>
                            <div class="user-progress">
                                <small class="fw-semibold">{{ $product->total_sold }} Terjual</small>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="text-center text-muted">Belum ada data penjualan.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="col-md-12 col-lg-4 order-2 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0 me-2">Pesanan Terbaru 🕒</h5>
            </div>
            <div class="card-body">
                <ul class="p-0 m-0">
                    @forelse($recentOrders as $order)
                    <li class="d-flex mb-4 pb-1 border-bottom">
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2 mb-2">
                            <div class="me-2">
                                <h6 class="mb-0">#{{ $order->nomor_antrian }} - {{ $order->nama_pelanggan ?: 'Umum' }}</h6>
                                <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="user-progress">
                                <span class="badge {{ $order->status_pesanan == 'SELESAI' ? 'bg-label-success' : 'bg-label-warning' }}">
                                    {{ $order->status_pesanan }}
                                </span>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="text-center text-muted">Belum ada pesanan hari ini.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Weekly Revenue Chart (Area)
        const weeklyData = @json($salesChart);
        const weeklyDates = weeklyData.map(d => new Date(d.date).toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric' }));
        const weeklyTotals = weeklyData.map(d => d.total);

        const revenueChartEl = document.querySelector('#weeklyRevenueChart');
        if (revenueChartEl) {
            const revenueChartOptions = {
                series: [{ name: 'Pendapatan', data: weeklyTotals }],
                chart: {
                    height: 300,
                    type: 'area', // or bar
                    toolbar: { show: false }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                xaxis: {
                    categories: weeklyDates,
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        formatter: function (val) { return 'Rp ' + (val / 1000) + 'k'; }
                    }
                },
                tooltip: {
                    y: { 
                        formatter: function(val) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(val); } 
                    }
                },
                colors: ['#696cff'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.9,
                        stops: [0, 90, 100]
                    }
                }
            };
            new ApexCharts(revenueChartEl, revenueChartOptions).render();
        }

        // 2. Category Pie Chart (Donut)
        const categoryData = @json($categorySales);
        const categoryLabels = categoryData.map(c => c.kategori || 'Lainnya');
        const categoryTotals = categoryData.map(c => parseInt(c.total_revenue));

        const pieChartEl = document.querySelector('#categoryPieChart');
        if (pieChartEl && categoryTotals.length > 0) {
            const pieChartOptions = {
                series: categoryTotals,
                labels: categoryLabels,
                chart: {
                    type: 'donut',
                    height: 300
                },
                legend: {
                    position: 'bottom',
                    markers: { width: 10, height: 10, radius: 10 }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: true,
                                name: { fontSize: '1.5rem', fontFamily: 'Public Sans' },
                                value: { 
                                    fontSize: '1rem', 
                                    fontFamily: 'Public Sans',
                                    formatter: function(val) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(val); }
                                },
                                total: {
                                    show: true,
                                    fontSize: '0.8125rem',
                                    label: 'Total',
                                    formatter: function (w) {
                                        const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
                                    }
                                }
                            }
                        }
                    }
                },
                tooltip: {
                    y: { 
                        formatter: function(val) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(val); } 
                    }
                },
                colors: ['#696cff', '#71dd37', '#03c3ec', '#8592a3', '#ff3e1d', '#ffab00'] // Sneat theme colors
            };
            new ApexCharts(pieChartEl, pieChartOptions).render();
        } else if (pieChartEl) {
            pieChartEl.innerHTML = '<div class="text-center text-muted py-5">Belum ada data penjualan kategori.</div>';
        }
    });
</script>
@endsection
