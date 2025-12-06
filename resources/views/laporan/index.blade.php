@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <h4 class="fw-bold">Laporan Keuangan & Omset 💰</h4>
    </div>

    <!-- Summary Cards -->
    <div class="col-md-4 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title text-white mb-0">Harian (Hari Ini)</h5>
                    <i class="bx bx-calendar-event fs-3"></i>
                </div>
                <h3 class="text-white mb-0">Rp {{ number_format($dailyRevenue, 0, ',', '.') }}</h3>
                <small class="text-white-50">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</small>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title text-white mb-0">Bulanan (Bulan Ini)</h5>
                    <i class="bx bx-calendar fs-3"></i>
                </div>
                <h3 class="text-white mb-0">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</h3>
                <small class="text-white-50">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</small>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title text-white mb-0">Tahunan (Tahun Ini)</h5>
                    <i class="bx bx-trending-up fs-3"></i>
                </div>
                <h3 class="text-white mb-0">Rp {{ number_format($yearlyRevenue, 0, ',', '.') }}</h3>
                <small class="text-white-50">Tahun {{ \Carbon\Carbon::now()->year }}</small>
            </div>
        </div>
    </div>

    <!-- Filter & Detail Table -->
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h5 class="mb-0">Rincian Transaksi</h5>
                <form action="{{ route('laporan.index') }}" method="GET" class="d-flex gap-2">
                    <div class="input-group">
                        <span class="input-group-text">Dari</span>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                    </div>
                    <div class="input-group">
                        <span class="input-group-text">Sampai</span>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('laporan.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-success">
                        <i class="bx bx-export me-1"></i> Export Excel (CSV)
                    </a>
                </form>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>No. Antrian</th>
                            <th>Pelanggan</th>
                            <th>Status</th>
                            <th class="text-end">Total Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporan as $item)
                        <tr>
                            <td>{{ $item->created_at->translatedFormat('d M Y H:i') }}</td>
                            <td><span class="badge bg-label-primary">{{ $item->nomor_antrian }}</span></td>
                            <td>{{ $item->nama_pelanggan ?: 'Umum' }}</td>
                            <td><span class="badge bg-success">LUNAS</span></td>
                            <td class="text-end fw-bold">Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Tidak ada data transaksi pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold fs-5">Total Periode Ini:</td>
                            <td class="text-end fw-bold fs-5 text-primary">Rp {{ number_format($totalPeriode, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
