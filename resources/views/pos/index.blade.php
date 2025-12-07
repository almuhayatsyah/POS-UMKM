@extends('layouts.app')

@section('content')
<div class="row">
    <!-- Product List -->
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-body p-2">
                <ul class="nav nav-pills nav-fill" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-order" aria-controls="navs-order" aria-selected="true">
                            <i class="bx bx-cart-alt me-1"></i> Pesanan Baru
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-bill" aria-controls="navs-bill" aria-selected="false">
                            <i class="bx bx-receipt me-1"></i> Daftar Tagihan
                            @if($pendingOrders->count() > 0)
                                <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-danger ms-1">{{ $pendingOrders->count() }}</span>
                            @endif
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content p-0">
            <!-- New Order Tab -->
            <div class="tab-pane fade show active" id="navs-order" role="tabpanel">
                <div class="row">
                    @foreach ($produk as $item)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 product-card" onclick="openOptionsModal({{ json_encode($item) }})">
                            <div class="card-body text-center">
                                <div class="avatar avatar-xl mx-auto mb-3">
                                    @if($item->url_gambar)
                                        <img src="{{ asset('storage/' . $item->url_gambar) }}" alt="{{ $item->nama_produk }}" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <span class="avatar-initial rounded-circle bg-label-primary"><i class="bx bx-coffee"></i></span>
                                    @endif
                                </div>
                                <h5 class="card-title">{{ $item->nama_produk }}</h5>
                                
                                @if($item->varian->count() > 0)
                                    <p class="card-text text-primary fw-bold">
                                        Mulai Rp {{ number_format($item->varian->min('harga'), 0, ',', '.') }}
                                    </p>
                                @else
                                    <p class="card-text text-primary fw-bold">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Pending Bills Tab -->
            <div class="tab-pane fade" id="navs-bill" role="tabpanel">
                <div class="row">
                    @forelse ($pendingOrders as $order)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 border-start border-5 border-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h5 class="card-title mb-1">Order #{{ $order->nomor_antrian }}</h5>
                                        <small class="text-muted">{{ $order->nama_pelanggan ?: 'Umum' }}</small>
                                    </div>
                                    <span class="badge bg-label-warning">{{ $order->status_pesanan }}</span>
                                </div>
                                <p class="mb-2 text-muted small">{{ $order->created_at->format('H:i') }}</p>
                                <h4 class="text-primary mb-3">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</h4>
                                <button class="btn btn-primary w-100" onclick="processExistingPayment({{ $order->id }}, '{{ $order->nomor_antrian }}', {{ $order->total_bayar }})">
                                    <i class="bx bx-wallet me-1"></i> Bayar Sekarang
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <div class="text-muted">Tidak ada tagihan yang belum dibayar.</div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Cart (Sidebar) -->
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Pesanan Baru</h5>
            </div>
            <div class="card-body d-flex flex-column">
                <div class="mb-3">
                    <label class="form-label">Nama Pelanggan</label>
                    <input type="text" class="form-control" id="nama_pelanggan" placeholder="Opsional">
                </div>
                
                <div class="flex-grow-1 overflow-auto mb-3" style="max-height: 400px;" id="cart-items">
                    <div class="text-center text-muted mt-5" id="empty-cart-msg">Belum ada item</div>
                </div>

                <div class="border-top pt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold">Total Estimasi:</span>
                        <span class="fw-bold text-primary" id="cart-total">Rp 0</span>
                    </div>
                    <button class="btn btn-primary w-100" id="btn-bayar" onclick="processOrder()" disabled>
                        <i class="bx bx-send me-1"></i> Proses ke Dapur
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Options Modal -->
    <div class="modal fade" id="optionsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="optionsModalTitle">Pilih Opsi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="selectedProductId">
                    
                    <!-- Variants Section -->
                    <div id="variantsSection" class="mb-3" style="display: none;">
                        <label class="form-label fw-bold">Pilih Varian / Ukuran</label>
                        <div id="variantsList" class="d-flex flex-wrap gap-2">
                            <!-- Radio buttons injected here -->
                        </div>
                    </div>

                    <!-- Toppings Section -->
                    <div id="toppingsSection" class="mb-3">
                        <label class="form-label fw-bold">Tambah Topping (Opsional)</label>
                        <div id="toppingsList">
                            @foreach ($toppings as $topping)
                            <div class="form-check">
                                <input class="form-check-input topping-checkbox" type="checkbox" value="{{ $topping->id }}" data-price="{{ $topping->harga }}" data-name="{{ $topping->nama_topping }}" id="topping-{{ $topping->id }}" onchange="updateModalTotal()">
                                <label class="form-check-label d-flex justify-content-between" for="topping-{{ $topping->id }}">
                                    <span>{{ $topping->nama_topping }}</span>
                                    <span class="text-muted">+Rp {{ number_format($topping->harga, 0, ',', '.') }}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4 border-top pt-3">
                        <span class="fw-bold">Total Item:</span>
                        <span class="fw-bold text-primary fs-4" id="modalTotal">Rp 0</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary w-100" onclick="addToCartFromModal()">Tambah ke Pesanan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal (New Order) -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Kirim pesanan ini ke dapur?</p>
                    <div class="alert alert-info mb-0">
                        <small><i class="bx bx-info-circle me-1"></i> Status pembayaran akan menjadi <strong>Belum Lunas</strong>.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="confirmOrder()">Ya, Kirim</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal (Existing Order) -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Proses Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <h3 class="mb-1" id="pay-queue">A-001</h3>
                        <div class="display-4 text-primary fw-bold" id="pay-amount">Rp 0</div>
                    </div>
                    <input type="hidden" id="pay-id">
                    <p class="text-center">Pastikan uang tunai / pembayaran telah diterima.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success w-100" onclick="confirmPayment()">Terima Pembayaran & Selesaikan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Berhasil! 🎉</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-4" id="success-msg">Pesanan telah masuk ke antrian dapur.</p>
                    <div class="display-1 fw-bold text-primary mb-2" id="modal-queue-number">A-001</div>
                    
                    <!-- Print Button Container -->
                    <div id="print-container" class="mt-3" style="display:none;">
                        <button class="btn btn-outline-secondary" onclick="printBill()">
                            <i class="bx bx-printer me-1"></i> Cetak Struk
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary w-100" onclick="location.reload()">Selesai</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let cart = [];
let currentProduct = null;
let optionsModal = null;
let confirmModal = null;
let paymentModal = null;
let successModal = null;

document.addEventListener('DOMContentLoaded', function() {
    optionsModal = new bootstrap.Modal(document.getElementById('optionsModal'));
    successModal = new bootstrap.Modal(document.getElementById('successModal'));
});

// ... (Existing functions: openOptionsModal, updateModalTotal, addToCartFromModal, updateCartUI, changeQty) ...
// I will copy them here to ensure they are present, but for brevity in this tool call I'll assume I need to rewrite the whole script block or just the changed parts.
// Since I'm replacing the whole file content, I must include everything.

function openOptionsModal(product) {
    currentProduct = product;
    document.getElementById('optionsModalTitle').innerText = product.nama_produk;
    document.getElementById('selectedProductId').value = product.id;
    document.querySelectorAll('.topping-checkbox').forEach(cb => cb.checked = false);
    
    const variantsSection = document.getElementById('variantsSection');
    const variantsList = document.getElementById('variantsList');
    variantsList.innerHTML = '';

    if (product.varian && product.varian.length > 0) {
        variantsSection.style.display = 'block';
        product.varian.forEach((v, index) => {
            const checked = index === 0 ? 'checked' : '';
            const html = `
                <input type="radio" class="btn-check variant-radio" name="variant_option" id="variant-${v.id}" value="${v.id}" data-price="${v.harga}" data-name="${v.nama_varian}" ${checked} onchange="updateModalTotal()">
                <label class="btn btn-outline-primary" for="variant-${v.id}">${v.nama_varian} (Rp ${parseInt(v.harga).toLocaleString()})</label>
            `;
            variantsList.innerHTML += html;
        });
    } else {
        variantsSection.style.display = 'none';
    }
    updateModalTotal();
    optionsModal.show();
}

function updateModalTotal() {
    let total = 0;
    if (currentProduct.varian && currentProduct.varian.length > 0) {
        const selectedVariant = document.querySelector('input[name="variant_option"]:checked');
        if (selectedVariant) total += parseInt(selectedVariant.getAttribute('data-price'));
    } else {
        total += parseInt(currentProduct.harga_jual);
    }
    document.querySelectorAll('.topping-checkbox:checked').forEach(cb => {
        total += parseInt(cb.getAttribute('data-price'));
    });
    document.getElementById('modalTotal').innerText = 'Rp ' + total.toLocaleString();
}

function addToCartFromModal() {
    let price = 0;
    let variantId = null;
    let variantName = null;
    let toppings = [];

    if (currentProduct.varian && currentProduct.varian.length > 0) {
        const selectedVariant = document.querySelector('input[name="variant_option"]:checked');
        if (!selectedVariant) { alert('Silakan pilih varian!'); return; }
        variantId = selectedVariant.value;
        variantName = selectedVariant.getAttribute('data-name');
        price += parseInt(selectedVariant.getAttribute('data-price'));
    } else {
        price += parseInt(currentProduct.harga_jual);
    }

    document.querySelectorAll('.topping-checkbox:checked').forEach(cb => {
        price += parseInt(cb.getAttribute('data-price'));
        toppings.push({
            id: cb.value,
            name: cb.getAttribute('data-name'),
            price: parseInt(cb.getAttribute('data-price'))
        });
    });

    const toppingIds = toppings.map(t => t.id).sort().join(',');
    const uniqueKey = `${currentProduct.id}-${variantId}-${toppingIds}`;
    const existingItem = cart.find(item => item.uniqueKey === uniqueKey);
    
    if (existingItem) {
        existingItem.qty++;
    } else {
        cart.push({
            uniqueKey: uniqueKey,
            id: currentProduct.id,
            name: currentProduct.nama_produk,
            price: price,
            qty: 1,
            variant_id: variantId,
            variant_name: variantName,
            toppings: toppings
        });
    }
    optionsModal.hide();
    updateCartUI();
}

function updateCartUI() {
    const container = document.getElementById('cart-items');
    const btnBayar = document.getElementById('btn-bayar');
    const totalEl = document.getElementById('cart-total');
    container.innerHTML = '';
    let total = 0;

    if (cart.length === 0) {
        container.innerHTML = '<div class="text-center text-muted mt-5" id="empty-cart-msg">Belum ada item</div>';
        btnBayar.disabled = true;
        totalEl.innerText = 'Rp 0';
        return;
    }

    btnBayar.disabled = false;
    cart.forEach((item, index) => {
        total += item.price * item.qty;
        let detailsHtml = '';
        if (item.variant_name) detailsHtml += `<span class="badge bg-label-primary me-1">${item.variant_name}</span>`;
        item.toppings.forEach(t => { detailsHtml += `<span class="badge bg-label-warning me-1">+ ${t.name}</span>`; });

        const itemHtml = `
            <div class="d-flex justify-content-between align-items-start mb-2 pb-2 border-bottom">
                <div class="flex-grow-1">
                    <div class="fw-semibold">${item.name}</div>
                    <div class="mb-1">${detailsHtml}</div>
                    <small class="text-muted">Rp ${item.price.toLocaleString()}</small>
                </div>
                <div class="d-flex align-items-center ms-2">
                    <button class="btn btn-xs btn-outline-secondary px-2" onclick="changeQty(${index}, -1)">-</button>
                    <span class="mx-2">${item.qty}</span>
                    <button class="btn btn-xs btn-outline-secondary px-2" onclick="changeQty(${index}, 1)">+</button>
                </div>
            </div>
        `;
        container.innerHTML += itemHtml;
    });
    totalEl.innerText = 'Rp ' + total.toLocaleString();
}

function changeQty(index, delta) {
    cart[index].qty += delta;
    if (cart[index].qty <= 0) cart.splice(index, 1);
    updateCartUI();
}

// --- New Logic ---

function processOrder() {
    const modalEl = document.getElementById('confirmationModal');
    confirmModal = new bootstrap.Modal(modalEl);
    confirmModal.show();
}

function confirmOrder() {
    const nama = document.getElementById('nama_pelanggan').value;
    const btn = document.getElementById('btn-bayar');
    if(confirmModal) confirmModal.hide();
    btn.disabled = true;
    btn.innerText = 'Memproses...';

    fetch('{{ route("pos.store") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({ cart: cart, nama_pelanggan: nama })
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Error');
        return data;
    })
    .then(data => {
        if(data.success) {
            document.getElementById('modalCenterTitle').innerText = 'Pesanan Berhasil! 🎉';
            document.getElementById('modal-queue-number').innerText = data.queue;
            document.getElementById('modal-queue-number').style.display = 'block';
            document.getElementById('success-msg').innerText = 'Pesanan masuk ke dapur. Silakan proses pembayaran nanti.';
            successModal.show();
            cart = [];
            document.getElementById('nama_pelanggan').value = '';
            updateCartUI();
        }
    })
    .catch(error => alert('Error: ' + error.message))
    .finally(() => { btn.disabled = false; btn.innerText = 'Proses ke Dapur'; });
}

function processExistingPayment(id, queue, amount) {
    document.getElementById('pay-id').value = id;
    document.getElementById('pay-queue').innerText = queue;
    document.getElementById('pay-amount').innerText = 'Rp ' + parseInt(amount).toLocaleString();
    
    const modalEl = document.getElementById('paymentModal');
    paymentModal = new bootstrap.Modal(modalEl);
    paymentModal.show();
}

function confirmPayment() {
    const id = document.getElementById('pay-id').value;
    const queue = document.getElementById('pay-queue').innerText;
    if(paymentModal) paymentModal.hide();

    fetch(`/pos/pay/${id}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Error');
        return data;
    })
    .then(data => {
        if(data.success) {
            document.getElementById('modalCenterTitle').innerText = 'Pembayaran Berhasil! 💸';
            document.getElementById('modal-queue-number').innerText = queue;
            document.getElementById('modal-queue-number').style.display = 'block';
            document.getElementById('success-msg').innerText = 'Transaksi telah lunas dan selesai.';
            
            // Show Print Button and Store ID
            document.getElementById('print-container').style.display = 'block';
            document.getElementById('print-container').setAttribute('data-id', id);

            successModal.show();
        }
    })
    .catch(error => alert('Error: ' + error.message));
}

function printBill() {
    const id = document.getElementById('print-container').getAttribute('data-id');
    const btn = document.querySelector('#print-container button');
    
    // Disable button to prevent double click
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Mencetak...';

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
    .catch(error => alert('Error: ' + error.message))
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
    });
}
</script>

<style>
.product-card { cursor: pointer; transition: transform 0.2s; }
.product-card:hover { transform: translateY(-5px); border-color: #696cff; }
</style>
@endsection
