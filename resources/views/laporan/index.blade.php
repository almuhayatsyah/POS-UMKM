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

    <!-- Revenue Chart -->
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h5 class="mb-0">Grafik Pendapatan 📈</h5>
                <form action="{{ route('laporan.index') }}" method="GET" class="d-flex gap-2">
                    <div class="input-group">
                        <span class="input-group-text">Dari</span>
                        <input type="text" name="start_date" class="form-control date-picker" value="{{ $startDate }}" placeholder="DD/MM/YYYY">
                    </div>
                    <div class="input-group">
                        <span class="input-group-text">Sampai</span>
                        <input type="text" name="end_date" class="form-control date-picker" value="{{ $endDate }}" placeholder="DD/MM/YYYY">
                    </div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('laporan.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-success" title="Download CSV">
                        <i class="bx bx-export me-1"></i> Export
                    </a>
                </form>
            </div>
            <div class="card-body">
                <div id="revenueChart"></div>
            </div>
        </div>
    </div>

    <!-- Transaction Detail Table (Restored) -->
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Rincian Transaksi 📝</h5>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>jam</th>
                            <th>No. Antrian</th>
                            <th>Pelanggan</th>
                            <th>Nama Produk</th>
                            <th>Varian</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporan as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->created_at)->isoFormat('d/M/Y HH:mm') }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->created_at)->isoFormat('HH:mm') }}</td>
                            <td><span class="badge bg-label-primary">{{ $item->nomor_antrian }}</span></td>
                            <td>{{ $item->nama_pelanggan ?: 'Umum' }}</td>
                            <td>{{ $item->nama_produk }}</td>
                            <td>{{ $item->nama_varian ?: '-' }}</td>
                            <td class="text-center">{{ $item->jumlah }}</td>
                            <td class="text-end fw-bold">Rp {{ number_format($item->subtotal_item, 0, ',', '.') }}</td>
                            <td>
                                @if(auth()->user()->peran == 'ADMIN')
                                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger" onclick="confirmDelete('{{ route('laporan.destroy', $item->id) }}')">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                @endif
                            </td>
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
            <div class="card-footer">
                {{ $laporan->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
    
    <!-- Product Sales Detail Table -->
    <div class="col-12 mt-4">
        <div class="card">
             <div class="card-header">
                <h5 class="mb-0">Laporan Penjualan Produk 📦</h5>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Produk</th>
                            <th class="text-center">Terjual</th>
                            <th class="text-end">Total Omset</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($soldProducts as $product)
                        <tr>
                            <td>{{ $product->nama_produk }}</td>
                            <td class="text-center">{{ $product->total_qty }}</td>
                            <td class="text-end fw-bold">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4">Tidak ada data penjualan produk pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>





<!-- Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr(".date-picker", {
            altInput: true,
            altFormat: "d/m/Y",
            dateFormat: "Y-m-d",
            locale: "id",
            allowInput: true
        });

        // Revenue Chart
        const chartData = @json($dailySales);
        const categories = chartData.map(item => new Date(item.date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }));
        const data = chartData.map(item => item.total);

        const options = {
            series: [{
                name: 'Pendapatan',
                data: data
            }],
            chart: {
                height: 350,
                type: 'area',
                toolbar: { show: false }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth' },
            xaxis: {
                categories: categories,
                type: 'category'
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return "Rp " + new Intl.NumberFormat('id-ID').format(value);
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return "Rp " + new Intl.NumberFormat('id-ID').format(value);
                    }
                }
            },
            colors: ['#696cff'], // Primary color
        };

        const chart = new ApexCharts(document.querySelector("#revenueChart"), options);
        chart.render();
    });
</script>
@endsection
