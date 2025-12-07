@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Produk</h5>
        <a href="{{ route('produk.create') }}" class="btn btn-primary">Tambah Produk</a>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Gambar</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga Jual</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($produk as $item)
                <tr>
                    <td>
                        @if($item->url_gambar)
                            <img src="{{ asset('storage/' . $item->url_gambar) }}" alt="Img" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                        @else
                            <div class="avatar avatar-sm">
                                <span class="avatar-initial rounded-circle bg-label-secondary"><i class="bx bx-image"></i></span>
                            </div>
                        @endif
                    </td>
                    <td><strong>{{ $item->nama_produk }}</strong></td>
                    <td>{{ $item->kategori }}</td>
                    <td>Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $item->tersedia ? 'bg-label-success' : 'bg-label-secondary' }}">
                            {{ $item->tersedia ? 'Tersedia' : 'Habis' }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('produk.edit', $item->id) }}" class="btn btn-sm btn-icon btn-outline-primary" title="Edit">
                                <i class="bx bx-edit-alt"></i>
                            </a>
                            <form action="{{ route('produk.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
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
