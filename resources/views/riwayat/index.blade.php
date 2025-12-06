@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Riwayat Transaksi</h5>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>No. Antrian</th>
                    <th>Pelanggan</th>
                    <th>Total Bayar</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @foreach ($riwayat as $item)
                <tr>
                    <td>{{ $item->created_at->translatedFormat('l, d F Y H:i') }}</td>
                    <td><span class="badge bg-label-primary">{{ $item->nomor_antrian }}</span></td>
                    <td>{{ $item->nama_pelanggan ?: '-' }}</td>
                    <td>Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                    <td>
                        @if($item->status_pesanan == 'SELESAI')
                            <span class="badge bg-label-success">Selesai</span>
                        @elseif($item->status_pesanan == 'DIPROSES')
                            <span class="badge bg-label-warning">Diproses</span>
                        @else
                            <span class="badge bg-label-secondary">{{ $item->status_pesanan }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('riwayat.show', $item->id) }}" class="btn btn-sm btn-icon btn-outline-secondary" title="Lihat Detail">
                                <i class="bx bx-show"></i>
                            </a>
                            <form action="{{ route('riwayat.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat ini? Data yang dihapus tidak dapat dikembalikan.');">
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
    <div class="card-footer">
        {{ $riwayat->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
