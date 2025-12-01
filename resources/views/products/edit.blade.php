@extends('layouts.app')

@section('title', 'EDIT PRODUK')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h3>✏️ EDIT PRODUK</h3>
        <a href="{{ route('products.index') }}" class="btn-back">⬅ Kembali ke Daftar Produk</a>
    </div>

    <form method="POST" action="{{ route('products.update', $product->id) }}" class="product-form">
        @csrf
        @method('PUT')
        
        <div class="form-layout">
            <!-- Kolom Kiri - Informasi Dasar -->
            <div class="form-column">
                <div class="form-section">
                    <h4>📝 Informasi Dasar</h4>
                    
                    <div class="form-group">
                        <label for="name">📦 Nama Produk *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" 
                               class="form-input @error('name') error @enderror" required>
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="category">🏷️ Kategori *</label>
                        <select id="category" name="category" class="form-select @error('category') error @enderror" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Hot Coffee" {{ old('category', $product->category) == 'Hot Coffee' ? 'selected' : '' }}>Hot Coffee</option>
                            <option value="Cold Coffee" {{ old('category', $product->category) == 'Cold Coffee' ? 'selected' : '' }}>Cold Coffee</option>
                            <option value="Tea" {{ old('category', $product->category) == 'Tea' ? 'selected' : '' }}>Tea</option>
                            <option value="Snack" {{ old('category', $product->category) == 'Snack' ? 'selected' : '' }}>Snack</option>
                            <option value="Dessert" {{ old('category', $product->category) == 'Dessert' ? 'selected' : '' }}>Dessert</option>
                            <option value="Bakery" {{ old('category', $product->category) == 'Bakery' ? 'selected' : '' }}>Bakery</option>
                            <option value="Other" {{ old('category', $product->category) == 'Other' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('category')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="unit">📏 Satuan *</label>
                        <select id="unit" name="unit" class="form-select @error('unit') error @enderror" required>
                            <option value="">Pilih Satuan</option>
                            <option value="pcs" {{ old('unit', $product->unit) == 'pcs' ? 'selected' : '' }}>Pcs</option>
                            <option value="cup" {{ old('unit', $product->unit) == 'cup' ? 'selected' : '' }}>Cup</option>
                            <option value="glass" {{ old('unit', $product->unit) == 'glass' ? 'selected' : '' }}>Glass</option>
                            <option value="pack" {{ old('unit', $product->unit) == 'pack' ? 'selected' : '' }}>Pack</option>
                            <option value="box" {{ old('unit', $product->unit) == 'box' ? 'selected' : '' }}>Box</option>
                            <option value="kg" {{ old('unit', $product->unit) == 'kg' ? 'selected' : '' }}>Kg</option>
                            <option value="gram" {{ old('unit', $product->unit) == 'gram' ? 'selected' : '' }}>Gram</option>
                        </select>
                        @error('unit')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>🆔 Kode Produk</label>
                        <div class="readonly-field">{{ $product->product_code }}</div>
                        <small class="help-text">Kode produk tidak dapat diubah</small>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="form-section">
                    <h4>📝 Deskripsi Produk</h4>
                    <div class="form-group">
                        <label for="description">Deskripsi (Opsional)</label>
                        <textarea id="description" name="description" class="form-textarea @error('description') error @enderror" 
                                  placeholder="Deskripsi produk, bahan, atau catatan khusus..." 
                                  rows="4">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan - Harga & Stok -->
            <div class="form-column">
                <div class="form-section">
                    <h4>💰 Harga & Biaya</h4>
                    
                    <div class="form-group">
                        <label for="price">💰 Harga Jual (Rp) *</label>
                        <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" 
                               class="form-input @error('price') error @enderror" min="0" step="100" required oninput="calculateMargin()">
                        @error('price')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="cost">💸 Biaya Produksi (Rp) *</label>
                        <input type="number" id="cost" name="cost" value="{{ old('cost', $product->cost) }}" 
                               class="form-input @error('cost') error @enderror" min="0" step="100" required oninput="calculateMargin()">
                        @error('cost')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Preview Margin -->
                    <div class="preview-card">
                        <h5>📈 Preview Margin</h5>
                        <div class="preview-grid">
                            <div class="preview-item">
                                <span class="label">Margin:</span>
                                <span class="value" id="marginPreview">{{ number_format($product->margin, 1) }}%</span>
                            </div>
                            <div class="preview-item">
                                <span class="label">Profit/Unit:</span>
                                <span class="value" id="profitPreview">Rp {{ number_format($product->price - $product->cost, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h4>📊 Manajemen Stok</h4>
                    
                    <div class="form-group">
                        <label for="stock">📦 Stok Saat Ini *</label>
                        <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" 
                               class="form-input @error('stock') error @enderror" min="0" required>
                        @error('stock')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="min_stock">⚠️ Stok Minimum *</label>
                        <input type="number" id="min_stock" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" 
                               class="form-input @error('min_stock') error @enderror" min="0" required>
                        @error('min_stock')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <span class="checkmark"></span>
                            ✅ Aktifkan Produk
                        </label>
                        <small class="help-text">Produk non-aktif tidak akan muncul di laporan penjualan</small>
                    </div>

                    <!-- Status Info -->
                    <div class="status-info">
                        <div class="status-item {{ $product->is_low_stock ? 'warning' : 'normal' }}">
                            <span class="status-icon">📊</span>
                            <div class="status-content">
                                <div class="status-title">Status Stok</div>
                                <div class="status-desc">
                                    @if($product->is_low_stock)
                                        ⚠️ Stok rendah! Perlu restock
                                    @else
                                        ✅ Stok aman
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <a href="{{ route('products.index') }}" class="btn-cancel">❌ Batal</a>
            <button type="submit" class="btn-submit">💾 Update Produk</button>
        </div>
    </form>
</div>

<style>
.form-container {
    max-width: 1200px;
    margin: 0 auto;
}

.form-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 20px;
    background: linear-gradient(135deg, #b68f82, #9a7568);
    border-radius: 15px;
    color: white;
}

.form-header h3 {
    margin: 0;
    font-size: 24px;
}

.btn-back {
    padding: 10px 20px;
    background: #f5e8c0;
    color: #b68f82;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
    transition: all 0.3s ease;
}

.btn-back:hover {
    background: transparent;
    border: 2px solid #f5e8c0;
    color: #f5e8c0;
}

.product-form {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.form-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-bottom: 25px;
}

.form-column {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.form-section {
    background: #f8f4e9;
    padding: 25px;
    border-radius: 12px;
    border: 2px solid #e9d8c8;
}

.form-section h4 {
    margin: 0 0 20px 0;
    color: #b68f82;
    font-size: 16px;
    border-bottom: 2px solid #e9d8c8;
    padding-bottom: 10px;
    font-weight: 600;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 20px;
}

.form-group:last-child {
    margin-bottom: 0;
}

.form-group label {
    font-weight: 600;
    color: #b68f82;
    font-size: 14px;
    margin-bottom: 5px;
}

.form-input, .form-select, .form-textarea {
    padding: 12px 15px;
    border: 2px solid #e9d8c8;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: white;
    width: 100%;
    box-sizing: border-box;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
    outline: none;
    border-color: #b68f82;
    box-shadow: 0 0 0 3px rgba(182, 143, 130, 0.1);
}

.form-input.error, .form-select.error, .form-textarea.error {
    border-color: #dc3545;
    background: #f8d7da;
}

.error-message {
    color: #dc3545;
    font-size: 12px;
    font-weight: 500;
    margin-top: 5px;
}

.form-textarea {
    resize: vertical;
    min-height: 120px;
    font-family: inherit;
}

.readonly-field {
    padding: 12px 15px;
    background: #f8f9fa;
    border: 2px solid #e9d8c8;
    border-radius: 8px;
    font-size: 14px;
    color: #666;
    font-weight: 500;
}

.help-text {
    color: #666;
    font-size: 12px;
    margin-top: 5px;
    display: block;
}

.preview-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    border: 2px solid #b68f82;
    margin-top: 15px;
}

.preview-card h5 {
    margin: 0 0 15px 0;
    color: #b68f82;
    font-size: 14px;
    font-weight: 600;
}

.preview-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.preview-item {
    text-align: center;
    padding: 10px;
    background: #f8f4e9;
    border-radius: 8px;
}

.preview-item .label {
    display: block;
    font-size: 11px;
    color: #666;
    margin-bottom: 5px;
    font-weight: 500;
}

.preview-item .value {
    display: block;
    font-size: 16px;
    font-weight: bold;
    color: #b68f82;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    font-weight: 500;
    color: #5a4a42;
    padding: 10px 0;
}

.checkbox-label input[type="checkbox"] {
    display: none;
}

.checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid #b68f82;
    border-radius: 4px;
    position: relative;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.checkbox-label input[type="checkbox"]:checked + .checkmark {
    background: #b68f82;
}

.checkbox-label input[type="checkbox"]:checked + .checkmark:after {
    content: "✓";
    position: absolute;
    color: white;
    font-size: 14px;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.status-info {
    margin-top: 20px;
}

.status-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    border-radius: 8px;
    border: 2px solid transparent;
}

.status-item.normal {
    background: #d4edda;
    border-color: #c3e6cb;
}

.status-item.warning {
    background: #fff3cd;
    border-color: #ffeaa7;
}

.status-icon {
    font-size: 24px;
    flex-shrink: 0;
}

.status-content {
    flex: 1;
}

.status-title {
    font-size: 14px;
    font-weight: 600;
    color: #155724;
    margin-bottom: 2px;
}

.status-desc {
    font-size: 12px;
    color: #0c5460;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-top: 30px;
    padding-top: 25px;
    border-top: 2px solid #f0f0f0;
}

.btn-cancel {
    padding: 12px 25px;
    background: #6c757d;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
    transition: background 0.3s ease;
}

.btn-cancel:hover {
    background: #545b62;
    color: white;
}

.btn-submit {
    padding: 12px 30px;
    background: #b68f82;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s ease;
}

.btn-submit:hover {
    background: #9a7568;
}

/* Responsive */
@media (max-width: 968px) {
    .form-layout {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .form-section {
        padding: 20px;
    }
}

@media (max-width: 768px) {
    .form-container {
        padding: 0 15px;
    }
    
    .form-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
        padding: 15px;
    }
    
    .product-form {
        padding: 20px;
    }
    
    .preview-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-cancel, .btn-submit {
        width: 100%;
        text-align: center;
    }
}
</style>

<script>
function calculateMargin() {
    const price = parseFloat(document.getElementById('price').value) || 0;
    const cost = parseFloat(document.getElementById('cost').value) || 0;
    
    let margin = 0;
    let profit = 0;
    
    if (price > 0) {
        profit = price - cost;
        margin = (profit / price) * 100;
    }
    
    document.getElementById('marginPreview').textContent = margin.toFixed(1) + '%';
    document.getElementById('profitPreview').textContent = 'Rp ' + profit.toLocaleString('id-ID');
    
    // Update color based on margin
    const marginElement = document.getElementById('marginPreview');
    if (margin >= 50) {
        marginElement.style.color = '#155724';
    } else if (margin >= 30) {
        marginElement.style.color = '#856404';
    } else if (margin > 0) {
        marginElement.style.color = '#721c24';
    } else {
        marginElement.style.color = '#dc3545';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    calculateMargin();
});
</script>
@endsection