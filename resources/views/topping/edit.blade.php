@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Topping</h5>
        <a href="{{ route('topping.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('topping.update', $topping->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Topping</label>
                    <input type="text" class="form-control" name="nama_topping" value="{{ $topping->nama_topping }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Harga Tambahan</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" name="harga" value="{{ $topping->harga }}" required min="0">
                    </div>
                </div>
            </div>

            <hr class="my-4">
            
            <h5 class="mb-3">Resep / Komposisi Topping</h5>
            <div class="table-responsive mb-3">
                <table class="table table-bordered" id="resepTable">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Bahan Baku</th>
                            <th style="width: 30%;">Jumlah</th>
                            <th style="width: 20%;">Satuan</th>
                            <th style="width: 10%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Recipe rows -->
                    </tbody>
                </table>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addResepRow()">
                    <i class="bx bx-plus"></i> Tambah Bahan
                </button>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>

<script>
    let resepCount = 0;
    const bahanBakuList = @json($bahanBaku);
    const existingResep = @json($topping->resepTopping);

    function init() {
        if (existingResep.length > 0) {
            existingResep.forEach(r => {
                addResepRow(r.bahan_baku_id, r.jumlah);
            });
        } else {
            addResepRow();
        }
    }

    function addResepRow(bahanId = '', jumlah = '') {
        const table = document.querySelector('#resepTable tbody');
        let options = '<option value="">Pilih Bahan</option>';
        let selectedSatuan = '-';

        bahanBakuList.forEach(bahan => {
            const selected = bahan.id == bahanId ? 'selected' : '';
            if (selected) selectedSatuan = bahan.satuan;
            options += `<option value="${bahan.id}" data-satuan="${bahan.satuan}" ${selected}>${bahan.nama_bahan} (Stok: ${bahan.stok_saat_ini})</option>`;
        });

        const row = `
            <tr id="resep-row-${resepCount}">
                <td>
                    <select class="form-select" name="resep[${resepCount}][bahan_id]" onchange="updateSatuan(${resepCount}, this)" required>
                        ${options}
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control" name="resep[${resepCount}][jumlah]" value="${jumlah}" step="0.001" min="0.001" required placeholder="0">
                </td>
                <td>
                    <span id="satuan-${resepCount}" class="text-muted">${selectedSatuan}</span>
                </td>
                <td>
                    <button type="button" class="btn btn-icon btn-outline-danger btn-sm" onclick="removeResepRow(${resepCount})">
                        <i class="bx bx-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        table.insertAdjacentHTML('beforeend', row);
        resepCount++;
    }

    function removeResepRow(index) {
        document.getElementById(`resep-row-${index}`).remove();
    }

    function updateSatuan(index, select) {
        const option = select.options[select.selectedIndex];
        const satuan = option.getAttribute('data-satuan') || '-';
        document.getElementById(`satuan-${index}`).innerText = satuan;
    }

    init();
</script>
@endsection
