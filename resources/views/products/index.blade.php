@extends('layouts.app')

@section('title', 'MANAJEMEN PRODUK')

@section('content')
<div class="products-container">
    <div class="page-header">
        <h3>📦 MANAJEMEN PRODUK</h3>
        <a href="{{ route('products.create') }}" class="btn-primary">➕ Tambah Produk Baru</a>
    </div>

    <!-- Filter & Search -->
    <div class="filter-section">
        <form method="GET" action="{{ route('products.index') }}" class="filter-form">
            <div class="filter-controls">
                <div class="filter-group">
                    <input type="text" name="search" value="{{ $search }}" 
                           class="form-input" placeholder="🔍 Cari produk...">
                </div>
                <div class="filter-group">
                    <select name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <button type="submit" class="btn-apply">Cari</button>
                    <a href="{{ route('products.index') }}" class="btn-reset">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <h3>Total Produk</h3>
            <div class="value">{{ $products->count() }}</div>
        </div>
        <div class="summary-card">
            <h3>Produk Aktif</h3>
            <div class="value">{{ $products->where('is_active', true)->count() }}</div>
        </div>
        <div class="summary-card">
            <h3>Stok Rendah</h3>
            <div class="value">{{ $products->where('stock', '<=', \DB::raw('min_stock'))->where('is_active', true)->count() }}</div>
        </div>
        <div class="summary-card">
            <h3>Total Kategori</h3>
            <div class="value">{{ $categories->count() }}</div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="table-container">
        <div class="table-header">
            <h4>Data Produk</h4>
            <div class="table-actions">
                <span class="total-records">Total: {{ $products->count() }} produk</span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="products-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr class="{{ $product->is_low_stock ? 'low-stock' : '' }} {{ !$product->is_active ? 'inactive' : '' }}" data-product-id="{{ $product->id }}">
                        <td class="product-code">
                            <strong>{{ $product->product_code }}</strong>
                        </td>
                        <td class="product-name">
                            <div class="product-info">
                                <strong>{{ $product->name }}</strong>
                                @if($product->description)
                                    <small class="product-desc">{{ Str::limit($product->description, 50) }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="category-badge">{{ $product->category }}</span>
                        </td>
                        <td class="price">
                            <div class="price-amount">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            <small class="cost">Cost: Rp {{ number_format($product->cost, 0, ',', '.') }}</small>
                            <small class="margin">Margin: {{ number_format($product->margin, 1) }}%</small>
                        </td>
                        <td class="stock-info">
                            <div class="stock-container">
                                <div class="stock-details">
                                    <div class="stock-amount {{ $product->is_low_stock ? 'low' : 'normal' }}">
                                        {{ $product->stock }} {{ $product->unit }}
                                    </div>
                                    @if($product->min_stock > 0)
                                        <small class="min-stock">Min: {{ $product->min_stock }}</small>
                                    @endif
                                </div>
                                <button class="btn-update-stock" onclick="showStockModal('{{ $product->id }}', '{{ $product->stock }}', '{{ $product->unit }}')">
                                    ✏️
                                </button>
                            </div>
                        </td>
                        <td class="status">
                            <span class="status-badge {{ $product->is_active ? 'active' : 'inactive' }}">
                                {{ $product->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                            @if($product->is_low_stock && $product->is_active)
                                <span class="low-stock-badge">Stok Rendah</span>
                            @endif
                        </td>
                        <td class="actions">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn-edit" title="Edit">
                                ✏️ Edit
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('Hapus produk ini?')" title="Hapus">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="no-data">
                            <div class="empty-state">
                                <span class="icon">📦</span>
                                <h4>Belum ada produk</h4>
                                <p>Mulai dengan menambahkan produk pertama Anda</p>
                                <a href="{{ route('products.create') }}" class="btn-primary">Tambah Produk Pertama</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Update Stock -->
<div id="stockModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4>✏️ Update Stok Produk</h4>
            <span class="close" onclick="closeStockModal()">&times;</span>
        </div>
        <form id="stockForm" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="stock">Jumlah Stok:</label>
                    <input type="number" id="stock" name="stock" class="form-input" min="0" required>
                    <small>Satuan: <span id="stockUnit"></span></small>
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeStockModal()">Batal</button>
                <button type="submit" class="btn-save">💾 Simpan Stok</button>
            </div>
        </form>
    </div>
</div>

<style>
.products-container {
    padding: 0;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 20px;
    background: linear-gradient(135deg, #b68f82, #9a7568);
    border-radius: 15px;
    color: white;
}

.page-header h3 {
    margin: 0;
    font-size: 24px;
}

.btn-primary {
    padding: 12px 25px;
    background: #f5e8c0;
    color: #b68f82;
    text-decoration: none;
    border-radius: 10px;
    font-weight: bold;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.btn-primary:hover {
    background: transparent;
    border-color: #f5e8c0;
    color: #f5e8c0;
}

.filter-section {
    margin-bottom: 25px;
}

.filter-form {
    background: #f8f4e9;
    padding: 20px;
    border-radius: 15px;
    border: 2px solid #b68f82;
}

.filter-controls {
    display: flex;
    gap: 15px;
    align-items: end;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.filter-group:last-child {
    flex-direction: row;
    gap: 10px;
}

.filter-group label {
    font-size: 14px;
    color: #b68f82;
    font-weight: bold;
}

.form-input {
    padding: 12px 15px;
    border: 2px solid #b68f82;
    border-radius: 10px;
    min-width: 250px;
    background: white;
    color: #5a4a42;
    font-size: 14px;
}

.form-select {
    padding: 12px 15px;
    border: 2px solid #b68f82;
    border-radius: 10px;
    min-width: 200px;
    background: white;
    color: #5a4a42;
    font-size: 14px;
}

.btn-apply {
    padding: 12px 20px;
    background: #b68f82;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    white-space: nowrap;
}

.btn-reset {
    padding: 12px 20px;
    background: #6c757d;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
    white-space: nowrap;
}

.btn-apply:hover {
    background: #9a7568;
}

.btn-reset:hover {
    background: #545b62;
}

.summary-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.summary-card {
    background: linear-gradient(135deg, #b68f82, #9a7568);
    color: white;
    padding: 20px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 4px 12px rgba(182, 143, 130, 0.3);
}

.summary-card h3 {
    font-size: 14px;
    margin-bottom: 10px;
    opacity: 0.9;
    font-weight: 600;
}

.summary-card .value {
    font-size: 24px;
    font-weight: bold;
}

.table-container {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    background: #f8f4e9;
    border-bottom: 2px solid #b68f82;
}

.table-header h4 {
    margin: 0;
    color: #b68f82;
    font-size: 18px;
}

.total-records {
    color: #666;
    font-size: 14px;
}

.alert {
    padding: 15px 20px;
    margin: 0 25px 20px;
    border-radius: 8px;
    font-weight: 500;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.products-table {
    width: 100%;
    border-collapse: collapse;
}

.products-table th {
    background: #b68f82;
    color: white;
    padding: 15px 12px;
    text-align: left;
    font-weight: 600;
    font-size: 14px;
}

.products-table td {
    padding: 12px;
    border-bottom: 1px solid #f0f0f0;
}

.products-table tr:hover {
    background: #f8f4e9;
}

.products-table tr.low-stock {
    background: #fff3cd;
}

.products-table tr.inactive {
    background: #f8f9fa;
    opacity: 0.7;
}

.product-code {
    font-family: monospace;
    font-weight: bold;
    color: #b68f82;
}

.product-name {
    max-width: 200px;
}

.product-info strong {
    display: block;
    margin-bottom: 5px;
}

.product-desc {
    color: #666;
    font-size: 12px;
    display: block;
}

.category-badge {
    padding: 4px 8px;
    background: #e9d8c8;
    color: #8B4513;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
}

.price {
    text-align: right;
}

.price-amount {
    font-weight: bold;
    color: #b68f82;
    font-size: 14px;
}

.cost, .margin {
    display: block;
    font-size: 11px;
    color: #666;
}

.stock-info {
    text-align: center;
}

/* PERBAIKAN: Container untuk stok dan button update */
.stock-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    min-width: 120px;
}

.stock-details {
    flex: 1;
    text-align: left;
}

.stock-amount {
    font-weight: bold;
    font-size: 14px;
    margin-bottom: 2px;
}

.stock-amount.normal {
    color: #28a745;
}

.stock-amount.low {
    color: #dc3545;
}

.min-stock {
    display: block;
    font-size: 11px;
    color: #666;
}

/* PERBAIKAN: Button update stock yang lebih kecil dan di samping */
.btn-update-stock {
    padding: 6px 8px;
    background: #ffc107;
    color: #000;
    border: none;
    border-radius: 6px;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.3s ease;
    flex-shrink: 0;
    min-width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-update-stock:hover {
    background: #e0a800;
    transform: translateY(-1px);
}

.status {
    text-align: center;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.active {
    background: #d4edda;
    color: #155724;
}

.status-badge.inactive {
    background: #f8d7da;
    color: #721c24;
}

.low-stock-badge {
    display: block;
    padding: 2px 6px;
    background: #fff3cd;
    color: #856404;
    border-radius: 4px;
    font-size: 10px;
    margin-top: 4px;
}

.actions {
    display: flex;
    gap: 8px;
}

.btn-edit {
    padding: 6px 12px;
    background: #ffc107;
    color: #000;
    text-decoration: none;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-edit:hover {
    background: #e0a800;
    transform: translateY(-1px);
}

.btn-delete {
    padding: 6px 12px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-delete:hover {
    background: #c82333;
    transform: translateY(-1px);
}

.delete-form {
    margin: 0;
}

.no-data {
    text-align: center;
    padding: 60px 20px !important;
}

.empty-state {
    color: #666;
}

.empty-state .icon {
    font-size: 48px;
    margin-bottom: 15px;
    display: block;
}

.empty-state h4 {
    margin: 10px 0;
    color: #b68f82;
}

.empty-state p {
    margin-bottom: 20px;
    opacity: 0.8;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: white;
    margin: 10% auto;
    padding: 0;
    border-radius: 15px;
    width: 400px;
    max-width: 90%;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    background: #b68f82;
    color: white;
    border-radius: 15px 15px 0 0;
}

.modal-header h4 {
    margin: 0;
}

.close {
    font-size: 24px;
    cursor: pointer;
}

.modal-body {
    padding: 25px;
}

.modal-body .form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.modal-body .form-group label {
    font-weight: 600;
    color: #b68f82;
    font-size: 14px;
}

.modal-body .form-input {
    padding: 10px 12px;
    border: 2px solid #e9d8c8;
    border-radius: 8px;
    font-size: 14px;
    width: 100%;
}

.modal-body .form-input:focus {
    outline: none;
    border-color: #b68f82;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    padding: 20px 25px;
    border-top: 1px solid #f0f0f0;
}

.btn-cancel {
    padding: 10px 20px;
    background: #6c757d;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
}

.btn-cancel:hover {
    background: #545b62;
}

.btn-save {
    padding: 10px 20px;
    background: #b68f82;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
}

.btn-save:hover {
    background: #9a7568;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .filter-controls {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-group:last-child {
        flex-direction: row;
        justify-content: center;
    }
    
    .form-input, .form-select {
        min-width: auto;
        width: 100%;
    }
    
    .summary-cards {
        grid-template-columns: 1fr 1fr;
    }
    
    .actions {
        flex-direction: column;
        gap: 5px;
    }
    
    /* PERBAIKAN: Responsive untuk stock container */
    .stock-container {
        flex-direction: column;
        gap: 5px;
        align-items: flex-start;
    }
    
    .btn-update-stock {
        align-self: flex-end;
    }
}
</style>

<script>
let currentProductId = null;

function showStockModal(productId, currentStock, unit) {
    currentProductId = productId;
    document.getElementById('stock').value = currentStock;
    document.getElementById('stockUnit').textContent = unit;
    document.getElementById('stockForm').action = `/products/${productId}/update-stock`;
    document.getElementById('stockModal').style.display = 'block';
}

function closeStockModal() {
    document.getElementById('stockModal').style.display = 'none';
    currentProductId = null;
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('stockModal');
    if (event.target == modal) {
        closeStockModal();
    }
}

// Handle form submission
document.getElementById('stockForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const productId = currentProductId;
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => {
        if (response.ok) {
            closeStockModal();
            location.reload(); // Reload page to see updated stock
        } else {
            alert('Error updating stock');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating stock');
    });
});

// Add data-product-id to table rows for easier selection
document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('.products-table tbody tr');
    rows.forEach((row) => {
        const editLink = row.querySelector('.btn-edit');
        if (editLink) {
            const productId = editLink.href.split('/').pop();
            row.setAttribute('data-product-id', productId);
        }
    });
});
</script>
@endsection