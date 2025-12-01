@extends('layouts.app')

@section('title', 'TAMBAH PRODUK BARU')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h3>➕ TAMBAH PRODUK BARU</h3>
        <a href="{{ route('products.index') }}" class="btn-back">⬅ Kembali ke Daftar Produk</a>
    </div>

    <form method="POST" action="{{ route('products.store') }}" class="product-form">
        @csrf
        
        <div class="form-grid">
            <!-- Informasi Dasar -->
            <div class="form-section">
                <h4>📝 Informasi Dasar</h4>
                
                <div class="form-group">
                    <label for="name">📦 Nama Produk *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" 
                           class="form-input @error('name') error @enderror" 
                           placeholder="Contoh: Espresso, Cappuccino, Croissant" required>
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                <small class="help-text">🆔 Kode produk akan digenerate otomatis berdasarkan kategori</small>
                </div>

                <div class="form-group">
                    <label for="category">🏷️ Kategori *</label>
                    <select id="category" name="category" class="form-select @error('category') error @enderror" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Hot Coffee" {{ old('category') == 'Hot Coffee' ? 'selected' : '' }}>Hot Coffee</option>
                        <option value="Cold Coffee" {{ old('category') == 'Cold Coffee' ? 'selected' : '' }}>Cold Coffee</option>
                        <option value="Tea" {{ old('category') == 'Tea' ? 'selected' : '' }}>Tea</option>
                        <option value="Snack" {{ old('category') == 'Snack' ? 'selected' : '' }}>Snack</option>
                        <option value="Dessert" {{ old('category') == 'Dessert' ? 'selected' : '' }}>Dessert</option>
                        <option value="Bakery" {{ old('category') == 'Bakery' ? 'selected' : '' }}>Bakery</option>
                        <option value="Other" {{ old('category') == 'Other' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('category')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="unit">📏 Satuan *</label>
                    <select id="unit" name="unit" class="form-select @error('unit') error @enderror" required>
                        <option value="">Pilih Satuan</option>
                        <option value="pcs" {{ old('unit') == 'pcs' ? 'selected' : '' }}>Pcs</option>
                        <option value="cup" {{ old('unit') == 'cup' ? 'selected' : '' }}>Cup</option>
                        <option value="glass" {{ old('unit') == 'glass' ? 'selected' : '' }}>Glass</option>
                        <option value="pack" {{ old('unit') == 'pack' ? 'selected' : '' }}>Pack</option>
                        <option value="box" {{ old('unit') == 'box' ? 'selected' : '' }}>Box</option>
                        <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kg</option>
                        <option value="gram" {{ old('unit') == 'gram' ? 'selected' : '' }}>Gram</option>
                    </select>
                    @error('unit')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Harga & Biaya -->
            <div class="form-section">
                <h4>💰 Harga & Biaya</h4>
                
                <div class="form-group">
                    <label for="price">💰 Harga Jual (Rp) *</label>
                    <input type="number" id="price" name="price" value="{{ old('price') }}" 
                           class="form-input @error('price') error @enderror" 
                           placeholder="Harga jual ke customer" min="0" step="100" required>
                    @error('price')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="cost">💸 Biaya Produksi (Rp) *</label>
                    <input type="number" id="cost" name="cost" value="{{ old('cost') }}" 
                           class="form-input @error('cost') error @enderror" 
                           placeholder="Biaya produksi/bahan" min="0" step="100" required>
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
                            <span class="value" id="marginPreview">0%</span>
                        </div>
                        <div class="preview-item">
                            <span class="label">Profit/Unit:</span>
                            <span class="value" id="profitPreview">Rp 0</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stok -->
            <div class="form-section">
                <h4>📊 Manajemen Stok</h4>
                
                <div class="form-group">
                    <label for="stock">📦 Stok Awal *</label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}" 
                           class="form-input @error('stock') error @enderror" 
                           placeholder="Jumlah stok awal" min="0" required>
                    @error('stock')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="min_stock">⚠️ Stok Minimum *</label>
                    <input type="number" id="min_stock" name="min_stock" value="{{ old('min_stock', 0) }}" 
                           class="form-input @error('min_stock') error @enderror" 
                           placeholder="Stok minimum sebelum restock" min="0" required>
                    @error('min_stock')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                        ✅ Aktifkan Produk
                    </label>
                    <small class="help-text">Produk non-aktif tidak akan muncul di laporan penjualan</small>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="form-section full-width">
            <h4>📝 Deskripsi Produk</h4>
            <div class="form-group">
                <label for="description">Deskripsi (Opsional)</label>
                <textarea id="description" name="description" class="form-textarea @error('description') error @enderror" 
                          placeholder="Deskripsi produk, bahan, atau catatan khusus..." rows="4">{{ old('description') }}</textarea>
                @error('description')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="reset" class="btn-reset">🔄 Reset Form</button>
            <button type="submit" class="btn-submit">💾 Simpan Produk</button>
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

.form-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
    margin-bottom: 25px;
}

.form-section {
    background: #f8f4e9;
    padding: 20px;
    border-radius: 10px;
    border: 2px solid #e9d8c8;
}

.form-section h4 {
    margin: 0 0 15px 0;
    color: #b68f82;
    font-size: 16px;
    border-bottom: 1px solid #e9d8c8;
    padding-bottom: 8px;
}

.form-section.full-width {
    grid-column: 1 / -1;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-bottom: 15px;
}

.form-group:last-child {
    margin-bottom: 0;
}

.form-group label {
    font-weight: 600;
    color: #b68f82;
    font-size: 14px;
}

.form-input, .form-select, .form-textarea {
    padding: 12px 15px;
    border: 2px solid #e9d8c8;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: white;
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
}

.form-textarea {
    resize: vertical;
    min-height: 100px;
}

.preview-card {
    background: white;
    padding: 15px;
    border-radius: 8px;
    border: 2px solid #b68f82;
    margin-top: 10px;
}

.preview-card h5 {
    margin: 0 0 10px 0;
    color: #b68f82;
    font-size: 14px;
}

.preview-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.preview-item {
    text-align: center;
}

.preview-item .label {
    display: block;
    font-size: 11px;
    color: #666;
    margin-bottom: 4px;
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
    gap: 10px;
    cursor: pointer;
    font-weight: 500;
    color: #5a4a42;
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

.help-text {
    color: #666;
    font-size: 12px;
    margin-top: 5px;
    display: block;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #f0f0f0;
}

.btn-reset {
    padding: 12px 25px;
    background: #6c757d;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s ease;
}

.btn-reset:hover {
    background: #545b62;
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
@media (max-width: 1024px) {
    .form-grid {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .preview-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const priceInput = document.getElementById('price');
    const costInput = document.getElementById('cost');
    const marginPreview = document.getElementById('marginPreview');
    const profitPreview = document.getElementById('profitPreview');

    function calculateMargin() {
        const price = parseFloat(priceInput.value) || 0;
        const cost = parseFloat(costInput.value) || 0;
        
        let margin = 0;
        let profit = 0;
        
        if (price > 0) {
            profit = price - cost;
            margin = (profit / price) * 100;
        }
        
        marginPreview.textContent = margin.toFixed(1) + '%';
        profitPreview.textContent = 'Rp ' + profit.toLocaleString('id-ID');
        
        // Update color based on margin
        if (margin >= 50) {
            marginPreview.style.color = '#155724';
        } else if (margin >= 30) {
            marginPreview.style.color = '#856404';
        } else if (margin > 0) {
            marginPreview.style.color = '#721c24';
        } else {
            marginPreview.style.color = '#dc3545';
        }
    }

    priceInput.addEventListener('input', calculateMargin);
    costInput.addEventListener('input', calculateMargin);
    
    // Initial calculation
    calculateMargin();
});
</script>
@endsection