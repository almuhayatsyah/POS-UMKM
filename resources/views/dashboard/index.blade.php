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
                <h5 class="card-title m-0 me-2">Produk Terlaris 🔥</h5>
            </div>
            <div class="card-body">
                <ul class="p-0 m-0">
                    @forelse($topProducts as $product)
                    <li class="d-flex mb-4 pb-1">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-trophy"></i></span>
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
@endsection
