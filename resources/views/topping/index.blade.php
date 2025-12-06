@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Topping</h5>
        <a href="{{ route('topping.create') }}" class="btn btn-primary">Tambah Topping</a>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nama Topping</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($toppings as $item)
                <tr>
                    <td><strong>{{ $item->nama_topping }}</strong></td>
                    <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('topping.edit', $item->id) }}" class="btn btn-sm btn-icon btn-outline-primary" title="Edit">
                                <i class="bx bx-edit-alt"></i>
                            </a>
                            <form action="{{ route('topping.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus topping ini?');">
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
