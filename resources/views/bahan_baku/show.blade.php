@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Bahan Baku</h5>
                <a href="{{ route('bahan-baku.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-start align-items-sm-center gap-4">
                    <div class="avatar avatar-xl">
                        <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-package fs-1"></i></span>
                    </div>
                    <div class="button-wrapper">
                        <h3 class="mb-1">{{ $bahanBaku->nama_bahan }}</h3>
                        <p class="text-muted mb-0">Satuan: {{ $bahanBaku->satuan }}</p>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-6 mb-4">
                        <label class="form-label">Stok Saat Ini</label>
                        <div class="d-flex align-items-center">
                            <h4 class="mb-0 me-2 {{ $bahanBaku->stok_saat_ini <= $bahanBaku->stok_minimum ? 'text-danger' : 'text-success' }}">
                                {{ $bahanBaku->stok_saat_ini }}
                            </h4>
                            <span class="text-muted">{{ $bahanBaku->satuan }}</span>
                        </div>
                        @if($bahanBaku->stok_saat_ini <= $bahanBaku->stok_minimum)
                            <small class="text-danger fw-bold"><i class="bx bx-error"></i> Stok Menipis!</small>
                        @else
                            <small class="text-success"><i class="bx bx-check-circle"></i> Stok Aman</small>
                        @endif
                    </div>
                    <div class="col-6 mb-4">
                        <label class="form-label">Stok Minimum</label>
                        <h4 class="mb-0">{{ $bahanBaku->stok_minimum }} <span class="fs-6 text-muted">{{ $bahanBaku->satuan }}</span></h4>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Harga Beli Terakhir</label>
                        <h4 class="mb-0">Rp {{ number_format($bahanBaku->harga_beli_terakhir, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Digunakan dalam Produk</h5>
                <small class="text-muted">Daftar produk yang menggunakan bahan ini</small>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Takaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bahanBaku->resep as $resep)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-xs me-2">
                                        <span class="avatar-initial rounded-circle bg-label-warning"><i class="bx bx-coffee"></i></span>
                                    </div>
                                    <strong>{{ $resep->produk->nama_produk }}</strong>
                                </div>
                            </td>
                            <td>{{ $resep->jumlah_bahan }} {{ $bahanBaku->satuan }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">Belum digunakan di produk manapun.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
