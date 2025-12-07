<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $pesanan->nomor_antrian }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            margin: 0;
            padding: 10px;
            width: 58mm; /* Standard Thermal Paper Width */
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 10px;
        }
        .bold {
            font-weight: bold;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        .item-row {
            display: flex;
            justify-content: space-between;
        }
        .item-name {
            display: block;
        }
        .totals {
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
        }
        @media print {
            body { margin: 0; padding: 0; }
            @page { margin: 0; size: auto; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <div class="bold">UMKM COFFEE</div>
        <div>Jl. Contoh No. 123</div>
        <div>Indonesia</div>
    </div>

    <div class="divider"></div>

    <div>
        No: {{ $pesanan->nomor_antrian }}<br>
        Tgl: {{ $pesanan->created_at->format('d/m/Y H:i') }}<br>
        Plg: {{ $pesanan->nama_pelanggan ?: 'Umum' }}
    </div>

    <div class="divider"></div>

    @foreach ($pesanan->detailPesanan as $detail)
    <div class="item">
        <div class="item-name">
            {{ $detail->produk->nama_produk }}
            @if($detail->produkVarian) ({{ $detail->produkVarian->nama_varian }}) @endif
        </div>
        <div class="item-row">
            <span>{{ $detail->jumlah }} x {{ number_format($detail->harga_satuan, 0, ',', '.') }}</span>
            <span>{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
        </div>
        @if(!empty($detail->toppings))
            @foreach($detail->toppings as $topping)
            <div class="item-row" style="padding-left: 10px; font-size: 10px; color: #555;">
                <span>+ {{ $topping['name'] }}</span>
            </div>
            @endforeach
        @endif
    </div>
    @endforeach

    <div class="divider"></div>

    <div class="totals bold">
        <span>TOTAL</span>
        <span>Rp {{ number_format($pesanan->total_bayar, 0, ',', '.') }}</span>
    </div>
    @if($pesanan->nominal_bayar)
    <div class="totals">
        <span>BAYAR</span>
        <span>Rp {{ number_format($pesanan->nominal_bayar, 0, ',', '.') }}</span>
    </div>
    <div class="totals">
        <span>KEMBALI</span>
        <span>Rp {{ number_format($pesanan->kembalian, 0, ',', '.') }}</span>
    </div>
    @endif

    <div class="divider"></div>

    <div class="footer">
        <div>Terima Kasih</div>
        <div>Silakan Datang Kembali</div>
    </div>
</body>
</html>
