@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Tambah Produk Baru</h5>
        <a href="{{ route('produk.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
    </div>
    <div class="card-body">
        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" class="form-control" name="nama_produk" required placeholder="Contoh: Kopi Susu Gula Aren">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kategori</label>
                    <input type="text" class="form-control" name="kategori" placeholder="Contoh: Minuman Coffee">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Foto Produk (Menu)</label>
                <input type="file" class="form-control" name="image" accept="image/*">
                <div class="form-text">Format: JPG, PNG, GIF. Maks: 2MB.</div>
            </div>

            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="hasVariants" onchange="toggleVariants()">
                <label class="form-check-label" for="hasVariants">Produk memiliki varian (ukuran/tipe)?</label>
            </div>

            <!-- Single Price Input -->
            <div class="mb-3" id="singlePriceDiv">
                <label class="form-label">Harga Jual</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" class="form-control" name="harga_jual" id="harga_jual_input" min="0" placeholder="0">
                </div>
            </div>

            <!-- Variants Section -->
            <div class="mb-4" id="variantsDiv" style="display: none;">
                <label class="form-label">Daftar Varian</label>
                <div class="table-responsive">
                    <table class="table table-bordered" id="variantsTable">
                        <thead>
                            <tr>
                                <th>Nama Varian (Contoh: M, L, Jumbo)</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Variant rows will be added here -->
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="addVariantRow()">
                        <i class="bx bx-plus"></i> Tambah Varian
                    </button>
                </div>
            </div>

            <hr class="my-4">
            
            <h5 class="mb-3">Resep / Komposisi</h5>
            <p class="text-muted small">Tentukan bahan baku yang digunakan. Jika produk memiliki varian, pilih varian mana yang menggunakan bahan ini.</p>

            <div class="table-responsive mb-3">
                <table class="table table-bordered" id="resepTable">
                    <thead>
                        <tr>
                            <th style="width: 30%;">Bahan Baku</th>
                            <th style="width: 25%;">Untuk Varian</th>
                            <th style="width: 20%;">Jumlah</th>
                            <th style="width: 10%;">Satuan</th>
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

            <button type="submit" class="btn btn-primary">Simpan Produk</button>
        </form>
    </div>
</div>

<script>
    let variantCount = 0;
    let resepCount = 0;
    const bahanBakuList = @json($bahanBaku);

    function toggleVariants() {
        const hasVariants = document.getElementById('hasVariants').checked;
        const singlePriceDiv = document.getElementById('singlePriceDiv');
        const variantsDiv = document.getElementById('variantsDiv');
        const hargaInput = document.getElementById('harga_jual_input');

        if (hasVariants) {
            singlePriceDiv.style.display = 'none';
            variantsDiv.style.display = 'block';
            hargaInput.value = ''; 
            hargaInput.required = false;
            if (variantCount === 0) addVariantRow(); // Add one row by default
        } else {
            singlePriceDiv.style.display = 'block';
            variantsDiv.style.display = 'none';
            hargaInput.required = true;
            document.querySelector('#variantsTable tbody').innerHTML = '';
            variantCount = 0;
        }
        updateResepScopeOptions();
    }

    function addVariantRow() {
        const table = document.querySelector('#variantsTable tbody');
        const row = `
            <tr id="variant-row-${variantCount}">
                <td>
                    <input type="text" class="form-control variant-name" name="variants[${variantCount}][nama]" placeholder="Nama Varian" required oninput="updateResepScopeOptions()">
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" name="variants[${variantCount}][harga]" placeholder="0" required min="0">
                    </div>
                </td>
                <td>
                    <button type="button" class="btn btn-icon btn-outline-danger btn-sm" onclick="removeVariantRow(${variantCount})">
                        <i class="bx bx-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        table.insertAdjacentHTML('beforeend', row);
        variantCount++;
        updateResepScopeOptions();
    }

    function removeVariantRow(index) {
        document.getElementById(`variant-row-${index}`).remove();
        updateResepScopeOptions();
    }

    function addResepRow() {
        const table = document.querySelector('#resepTable tbody');
        let options = '<option value="">Pilih Bahan</option>';
        bahanBakuList.forEach(bahan => {
            options += `<option value="${bahan.id}" data-satuan="${bahan.satuan}">${bahan.nama_bahan} (Stok: ${bahan.stok_saat_ini})</option>`;
        });

        const row = `
            <tr id="resep-row-${resepCount}">
                <td>
                    <select class="form-select" name="resep[${resepCount}][bahan_id]" onchange="updateSatuan(${resepCount}, this)" required>
                        ${options}
                    </select>
                </td>
                <td>
                    <select class="form-select scope-select" name="resep[${resepCount}][scope]">
                        <!-- Options populated via JS -->
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control" name="resep[${resepCount}][jumlah]" step="0.001" min="0.001" required placeholder="0">
                </td>
                <td>
                    <span id="satuan-${resepCount}" class="text-muted">-</span>
                </td>
                <td>
                    <button type="button" class="btn btn-icon btn-outline-danger btn-sm" onclick="removeResepRow(${resepCount})">
                        <i class="bx bx-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        table.insertAdjacentHTML('beforeend', row);
        
        // Populate scope options for this new row
        const newSelect = table.lastElementChild.querySelector('.scope-select');
        populateScopeOptions(newSelect);
        
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

    function updateResepScopeOptions() {
        const selects = document.querySelectorAll('.scope-select');
        selects.forEach(select => {
            const currentVal = select.value;
            populateScopeOptions(select);
            select.value = currentVal; // Try to keep selection
        });
    }

    function populateScopeOptions(select) {
        const hasVariants = document.getElementById('hasVariants').checked;
        select.innerHTML = '';

        if (!hasVariants) {
            select.innerHTML = '<option value="base" selected>Produk Dasar</option>';
            select.disabled = true; // Lock to base if no variants
            // Note: We still send 'base' or handle null in controller
        } else {
            select.disabled = false;
            // Add Base option (maybe common ingredients?)
            // For now, let's force user to choose variant if variants exist to avoid ambiguity
            // Or allow "Semua Varian" if we want to support that later.
            // Let's stick to specific variants for now.
            
            const variantRows = document.querySelectorAll('#variantsTable tbody tr');
            variantRows.forEach((row, index) => {
                // Extract the index from the row ID "variant-row-X"
                const rowId = row.id;
                const variantIndex = rowId.replace('variant-row-', '');
                const nameInput = row.querySelector('.variant-name');
                const name = nameInput.value || `Varian ${index + 1}`;
                
                const option = document.createElement('option');
                option.value = `variant_index_${variantIndex}`;
                option.text = name;
                select.appendChild(option);
            });
            
            if (variantRows.length === 0) {
                 select.innerHTML = '<option value="" disabled selected>Tambah varian dulu</option>';
            }
        }
    }

    // Initialize
    addResepRow();
    toggleVariants();
</script>
@endsection
