@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Tambah Produk Baru</h5>
        <a href="{{ route('produk.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
    </div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible" role="alert">
                <i class="bx bx-error-circle me-1"></i> Terdapat kesalahan pada input Anda. Silakan periksa kembali.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                <i class="bx bx-error-circle me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" class="form-control @error('nama_produk') is-invalid @enderror" name="nama_produk" required value="{{ old('nama_produk') }}" placeholder="Contoh: Kopi Susu Gula Aren">
                    @error('nama_produk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kategori</label>
                    <input type="text" class="form-control @error('kategori') is-invalid @enderror" name="kategori" value="{{ old('kategori') }}" placeholder="Contoh: Minuman Coffee">
                    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Foto Produk (Menu)</label>
                <input type="file" class="form-control @error('image') is-invalid @enderror" name="image" accept="image/*">
                <div class="form-text">Format: JPG, PNG, GIF. Maks: 2MB.</div>
                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="hasVariants" onchange="toggleVariants()" {{ old('variants') ? 'checked' : '' }}>
                <label class="form-check-label" for="hasVariants">Produk memiliki varian (ukuran/tipe)?</label>
            </div>

            <!-- Single Price Input -->
            <div class="mb-3" id="singlePriceDiv">
                <label class="form-label">Harga Jual</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" class="form-control @error('harga_jual') is-invalid @enderror" name="harga_jual" id="harga_jual_input" min="0" value="{{ old('harga_jual') }}" placeholder="0">
                    @error('harga_jual') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                    @if($errors->has('variants')) 
                        <div class="text-danger small mt-1">{{ $errors->first('variants') }}</div>
                    @endif
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
                            <th style="width: 20%;">Jumlah <i class="bx bx-info-circle text-primary" title="Sesuaikan dengan satuan bahan baku (Misal: Gram)"></i></th>
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
    
    // Get old input from Laravel
    const oldVariants = @json(old('variants', []));
    const oldResep = @json(old('resep', []));

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
            
            // Only add default row if count is 0 AND we are not repopulating
            if (variantCount === 0 && oldVariants.length === 0) {
                addVariantRow(); 
            }
        } else {
            singlePriceDiv.style.display = 'block';
            variantsDiv.style.display = 'none';
            hargaInput.required = true;
            // Only clear if user manually toggles off, not on init (though users unlikely to toggle off after error)
            // But we should be careful. For now, let's leave it.
        }
        updateResepScopeOptions();
    }

    function addVariantRow(data = null) {
        const table = document.querySelector('#variantsTable tbody');
        const nama = data ? data.nama : '';
        const harga = data ? data.harga : '';
        
        const row = `
            <tr id="variant-row-${variantCount}">
                <td>
                    <input type="text" class="form-control variant-name" name="variants[${variantCount}][nama]" placeholder="Nama Varian" required oninput="updateResepScopeOptions()" value="${nama}">
                </td>
                <td>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" name="variants[${variantCount}][harga]" placeholder="0" required min="0" value="${harga}">
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

    function addResepRow(data = null) {
        const table = document.querySelector('#resepTable tbody');
        let options = '<option value="">Pilih Bahan</option>';
        
        const selectedBahanId = data ? data.bahan_id : '';
        const selectedScope = data ? data.scope : '';
        const jumlah = data ? data.jumlah : '';
        
        bahanBakuList.forEach(bahan => {
            const selected = (bahan.id == selectedBahanId) ? 'selected' : '';
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
                    <select class="form-select scope-select" name="resep[${resepCount}][scope]" data-selected="${selectedScope}">
                        <!-- Options populated via JS -->
                    </select>
                </td>
                <td>
                    <input type="number" class="form-control" name="resep[${resepCount}][jumlah]" step="0.001" min="0.001" required placeholder="0" value="${jumlah}">
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
        
        // Update satuan text immediately if value exists
        if(selectedBahanId) {
             const selectEl = table.lastElementChild.querySelector('select[name*="bahan_id"]');
             updateSatuan(resepCount, selectEl);
        }

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
            const currentVal = select.value || select.getAttribute('data-selected');
            populateScopeOptions(select);
            if(currentVal) select.value = currentVal; 
        });
    }

    function populateScopeOptions(select) {
        const hasVariants = document.getElementById('hasVariants').checked;
        select.innerHTML = '';

        if (!hasVariants) {
            select.innerHTML = '<option value="base" selected>Produk Dasar</option>';
            // select.disabled = true; // Don't disable, just force value
        } else {
            select.disabled = false;
            
            const variantRows = document.querySelectorAll('#variantsTable tbody tr');
            variantRows.forEach((row, index) => {
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

    // Initialize logic
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Restore Variants First
        if (oldVariants && Object.keys(oldVariants).length > 0) {
             Object.values(oldVariants).forEach(v => {
                 addVariantRow(v);
             });
             // Ensure checkboxes and display are correct
             document.getElementById('hasVariants').checked = true;
             toggleVariants(); 
        } else {
             toggleVariants(); // Will add default row if no variants
        }

        // 2. Restore Recipes
        if (oldResep && Object.keys(oldResep).length > 0) {
             Object.values(oldResep).forEach(r => {
                 addResepRow(r);
             });
        } else {
             addResepRow(); // Default empty row
        }
        
        // 3. Trigger scope update one last time to sync names
        updateResepScopeOptions();
    });
</script>
@endsection
