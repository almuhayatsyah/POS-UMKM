@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Bahan Baku</h5>
        <a href="{{ route('bahan-baku.create') }}" class="btn btn-primary">Tambah Bahan</a>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nama Bahan</th>
                    <th>Stok</th>
                    <th>Satuan</th>
                    <th>Harga Beli Terakhir</th>
                    <th>Stok Minimum</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($bahanBaku as $item)
                <tr>
                    <td><strong>{{ $item->nama_bahan }}</strong></td>
                    <td>
                        <span class="badge {{ $item->stok_saat_ini <= $item->stok_minimum ? 'bg-label-danger' : 'bg-label-success' }}">
                            {{ $item->stok_saat_ini }}
                        </span>
                    </td>
                    <td>{{ $item->satuan }}</td>
                    <td>Rp {{ number_format($item->harga_beli_terakhir, 0, ',', '.') }}</td>
                    <td>{{ $item->stok_minimum }}
                         <p class="text-muted mb-0">Satuan: {{ $item->satuan }}</p>
                    </td>
                    <td>{{ $item->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('bahan-baku.show', $item->id) }}" class="btn btn-sm btn-icon btn-outline-info" title="Detail & Monitoring">
                                <i class="bx bx-show"></i>
                            </a>
                            <a href="{{ route('bahan-baku.edit', $item->id) }}" class="btn btn-sm btn-icon btn-outline-primary" title="Edit">
                                <i class="bx bx-edit-alt"></i>
                            </a>
                            <form action="{{ route('bahan-baku.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus bahan baku ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-icon btn-outline-danger" title="Hapus">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
