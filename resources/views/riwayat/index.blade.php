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
                    <th>jam</th>
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
                    <td>{{ $item->created_at->isoFormat('dddd, D MMMM Y HH:mm') }}</td>
                    <td>{{ $item->created_at->isoFormat('HH:mm') }}</td>
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
                             <button type="button" class="btn btn-sm btn-icon btn-outline-primary" onclick="printBill({{ $item->id }})" title="Cetak Struk">
                                <i class="bx bx-printer"></i>
                            </button>
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

<script>
function printBill(id) {
    if(!confirm('Cetak ulang struk untuk pesanan ini?')) return;
    
    // Show loading state implies visual feedback, simplified here with alert flow or toast
    // Ideally we would change button state, but row buttons are tricky.
    
    fetch(`/pos/print/${id}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Error printing');
        return data;
    })
    .then(data => {
        if(data.success) {
            alert('Struk berhasil dicetak!');
        } else {
            alert('Gagal mencetak: ' + data.message);
        }
    })
    .catch(error => alert('Error: ' + error.message));
}
</script>
@endsection
