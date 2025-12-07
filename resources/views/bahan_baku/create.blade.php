@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tambah Bahan Baku Baru</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('bahan-baku.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="nama_bahan">Nama Bahan</label>
                        <input type="text" class="form-control" id="nama_bahan" name="nama_bahan" placeholder="Contoh: Alpukat Mentega" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="stok_saat_ini">Stok Awal</label>
                        <input type="number" step="0.01" class="form-control" id="stok_saat_ini" name="stok_saat_ini" placeholder="0" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="satuan">Satuan</label>
                        <select class="form-select" id="satuan" name="satuan" required>
                            <option value="KG">Kilogram (KG)</option>
                            <option value="GRAM">Gram (GR)</option>
                            <option value="LITER">Liter (L)</option>
                            <option value="ML">Mililiter (ML)</option>
                            <option value="PCS">Pcs/Buah</option>
                            <option value="KALENG">Kaleng</option>
                        </select>
                        <div class="form-text text-primary"><i class="bx bx-info-circle"></i> Disarankan menggunakan satuan terkecil (Contoh: <strong>GRAM</strong> atau <strong>ML</strong>) agar pengurangan stok lebih akurat saat transaksi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="harga_beli_terakhir">Harga Beli (Per Satuan)</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">Rp</span>
                            <input type="number" step="0.01" class="form-control" id="harga_beli_terakhir" name="harga_beli_terakhir" placeholder="10000" />
                        </div>
                        <div class="form-text">Opsional, untuk tracking cost.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="stok_minimum">Stok Minimum (Alert)</label>
                        <input type="number" step="0.01" class="form-control" id="stok_minimum" name="stok_minimum" value="5" required />
                        <div class="form-text">Sistem akan memberi peringatan jika stok di bawah angka ini.</div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('bahan-baku.index') }}" class="btn btn-outline-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
