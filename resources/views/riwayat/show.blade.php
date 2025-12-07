@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Transaksi #{{ $pesanan->nomor_antrian }}</h5>
                <a href="{{ route('riwayat.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-sm-6">
                        <h6 class="mb-1">Informasi Pesanan:</h6>
                        <p class="mb-1">Tanggal: <strong>{{ $pesanan->created_at->isoFormat('dddd, D MMMM Y HH:mm') }}</strong></p>
                        <p class="mb-1">Pelanggan: <strong>{{ $pesanan->nama_pelanggan ?: 'Umum' }}</strong></p>
                        <p class="mb-1">Status: 
                            @if($pesanan->status_pesanan == 'SELESAI')
                                <span class="badge bg-label-success">Selesai</span>
                            @else
                                <span class="badge bg-label-warning">{{ $pesanan->status_pesanan }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="table-responsive border-top">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pesanan->detailPesanan as $detail)
                            <tr>
                                <td>
                                    <strong>{{ $detail->produk->nama_produk }}</strong>
                                    @if($detail->produkVarian)
                                        <span class="badge bg-label-primary ms-1">{{ $detail->produkVarian->nama_varian }}</span>
                                    @endif
                                    
                                    @if(!empty($detail->toppings))
                                        <div class="mt-1">
                                            @foreach($detail->toppings as $topping)
                                                <span class="badge bg-label-warning me-1">+ {{ $topping['name'] }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">{{ $detail->jumlah }}</td>
                                <td class="text-end">Rp {{ number_format($detail->subtotal_item / $detail->jumlah, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($detail->subtotal_item, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total Bayar</td>
                                <td class="text-end fw-bold">Rp {{ number_format($pesanan->total_bayar, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
