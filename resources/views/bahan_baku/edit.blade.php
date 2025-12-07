@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-xl">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Bahan Baku</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('bahan-baku.update', $bahanBaku->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label" for="nama_bahan">Nama Bahan</label>
                        <input type="text" class="form-control" id="nama_bahan" name="nama_bahan" value="{{ $bahanBaku->nama_bahan }}" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="stok_saat_ini">Stok Saat Ini</label>
                        <input type="number" step="0.01" class="form-control" id="stok_saat_ini" name="stok_saat_ini" value="{{ $bahanBaku->stok_saat_ini }}" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="satuan">Satuan</label>
                        <select class="form-select" id="satuan" name="satuan" required>
                            <option value="KG" {{ $bahanBaku->satuan == 'KG' ? 'selected' : '' }}>Kilogram (KG)</option>
                            <option value="GRAM" {{ $bahanBaku->satuan == 'GRAM' ? 'selected' : '' }}>Gram (GR)</option>
                            <option value="LITER" {{ $bahanBaku->satuan == 'LITER' ? 'selected' : '' }}>Liter (L)</option>
                            <option value="ML" {{ $bahanBaku->satuan == 'ML' ? 'selected' : '' }}>Mililiter (ML)</option>
                            <option value="PCS" {{ $bahanBaku->satuan == 'PCS' ? 'selected' : '' }}>Pcs/Buah</option>
                            <option value="KALENG" {{ $bahanBaku->satuan == 'KALENG' ? 'selected' : '' }}>Kaleng</option>
                        </select>
                        <div class="form-text text-primary"><i class="bx bx-info-circle"></i> Disarankan menggunakan satuan terkecil (Contoh: <strong>GRAM</strong> atau <strong>ML</strong>) agar pengurangan stok lebih akurat saat transaksi.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="harga_beli_terakhir">Harga Beli (Per Satuan)</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">Rp</span>
                            <input type="number" step="0.01" class="form-control" id="harga_beli_terakhir" name="harga_beli_terakhir" value="{{ $bahanBaku->harga_beli_terakhir }}" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="stok_minimum">Stok Minimum (Alert)</label>
                        <input type="number" step="0.01" class="form-control" id="stok_minimum" name="stok_minimum" value="{{ $bahanBaku->stok_minimum }}" required />
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('bahan-baku.index') }}" class="btn btn-outline-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
