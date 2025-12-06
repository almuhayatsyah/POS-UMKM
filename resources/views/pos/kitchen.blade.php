@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 mb-4">
        <h4 class="fw-bold">Dapur / Kitchen Display</h4>
    </div>

    @forelse ($activeOrders as $order)
    <div class="col-md-4 mb-4">
        <div class="card h-100 {{ $order->status_pesanan == 'SIAP_SAJI' ? 'border-success' : 'border-warning' }} border-3">
            <div class="card-header d-flex justify-content-between align-items-center bg-lighter">
                <h5 class="mb-0">#{{ $order->nomor_antrian }}</h5>
                <span class="badge {{ $order->status_pesanan == 'SIAP_SAJI' ? 'bg-success' : 'bg-warning' }}">
                    {{ $order->status_pesanan }}
                </span>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Pelanggan:</strong> {{ $order->nama_pelanggan ?: 'Umum' }}</p>
                <p class="text-muted small">{{ $order->created_at->diffForHumans() }}</p>
                
                <ul class="list-group list-group-flush mb-3">
                    @foreach ($order->detailPesanan as $detail)
                    <li class="list-group-item px-0">
                        <div class="fw-bold">{{ $detail->produk->nama_produk }}</div>
                        @if($detail->produkVarian)
                            <small class="text-primary">{{ $detail->produkVarian->nama_varian }}</small><br>
                        @endif
                        @if(!empty($detail->toppings))
                            @foreach($detail->toppings as $topping)
                                <span class="badge bg-label-secondary">+ {{ $topping['name'] }}</span>
                            @endforeach
                        @endif
                        <div class="text-end">x {{ $detail->jumlah }}</div>
                    </li>
                    @endforeach
                </ul>

                @if($order->status_pesanan == 'DIPROSES')
                <form action="{{ route('pos.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="SIAP_SAJI">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bx bx-dish me-1"></i> Sajikan (Ready)
                    </button>
                </form>
                @else
                <div class="alert alert-success text-center mb-0">
                    <i class="bx bx-check-circle me-1"></i> Menunggu Pembayaran
                </div>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center">
        <div class="alert alert-secondary">Belum ada pesanan masuk.</div>
    </div>
    @endforelse
</div>

<script>
    // Auto refresh every 30 seconds
    setTimeout(function(){
       location.reload();
    }, 30000);
</script>
@endsection
