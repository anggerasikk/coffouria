@extends('layouts.app')

@section('title', 'TAMBAH LAPORAN PENJUALAN')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h3>➕ TAMBAH LAPORAN PENJUALAN</h3>
        <a href="{{ route('reports.index') }}" class="btn-back">⬅ Kembali ke Daftar Laporan</a>
    </div>

    <form method="POST" action="{{ route('reports.store') }}" class="report-form">
        @csrf
        
        <div class="form-grid">
            <!-- Informasi Dasar -->
            <div class="form-section">
                <h4>📅 Informasi Tanggal</h4>
                
                <div class="form-group">
                    <label for="report_date">📅 Tanggal Laporan *</label>
                    <input type="date" id="report_date" name="report_date" value="{{ old('report_date', date('Y-m-d')) }}" 
                           class="form-input @error('report_date') error @enderror" required>
                    @error('report_date')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Informasi Produk -->
            <div class="form-section">
                <h4>📦 Informasi Produk</h4>
                
                <div class="form-group">
                    <label for="product_id">📦 Pilih Produk *</label>
                    <select id="product_id" name="product_id" class="form-select @error('product_id') error @enderror" required onchange="loadProductData()">
                        <option value="">Pilih Produk</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" 
                                    data-price="{{ $product->price }}"
                                    data-cost="{{ $product->cost }}"
                                    data-stock="{{ $product->stock }}"
                                    {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} ({{ $product->product_code }}) - Stok: {{ $product->stock }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="quantity_sold">📊 Quantity Terjual *</label>
                    <input type="number" id="quantity_sold" name="quantity_sold" value="{{ old('quantity_sold') }}" 
                           class="form-input @error('quantity_sold') error @enderror" 
                           placeholder="Jumlah produk terjual" min="1" required oninput="calculateRevenue()">
                    @error('quantity_sold')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    <small id="stockInfo" class="help-text"></small>
                </div>
            </div>

            <!-- Informasi Harga -->
            <div class="form-section">
                <h4>💰 Informasi Harga</h4>
                
                <div class="price-preview">
                    <div class="price-item">
                        <label>Harga Satuan:</label>
                        <span id="unitPrice">Rp 0</span>
                    </div>
                    <div class="price-item">
                        <label>Biaya Satuan:</label>
                        <span id="unitCost">Rp 0</span>
                    </div>
                    <div class="price-item">
                        <label>Stok Tersedia:</label>
                        <span id="availableStock">0</span>
                    </div>
                </div>

                <!-- Preview Revenue -->
                <div class="preview-card">
                    <h5>📈 Preview Penjualan</h5>
                    <div class="preview-grid">
                        <div class="preview-item">
                            <span class="label">Total Pendapatan:</span>
                            <span class="value" id="totalRevenue">Rp 0</span>
                        </div>
                        <div class="preview-item">
                            <span class="label">Total Biaya:</span>
                            <span class="value" id="totalCost">Rp 0</span>
                        </div>
                        <div class="preview-item">
                            <span class="label">Profit:</span>
                            <span class="value" id="totalProfit">Rp 0</span>
                        </div>
                        <div class="preview-item">
                            <span class="label">Margin:</span>
                            <span class="value" id="totalMargin">0%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div class="form-section full-width">
            <h4>📝 Catatan</h4>
            <div class="form-group">
                <label for="notes">Catatan (Opsional)</label>
                <textarea id="notes" name="notes" class="form-textarea @error('notes') error @enderror" 
                          placeholder="Catatan tambahan tentang penjualan ini..." rows="3">{{ old('notes') }}</textarea>
                @error('notes')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="reset" class="btn-reset">🔄 Reset</button>
            <button type="submit" class="btn-submit">💾 Simpan Laporan</button>
        </div>
    </form>
</div>

<style>
.form-container {
    max-width: 1000px;
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

.report-form {
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
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
    min-height: 80px;
}

.price-preview {
    background: white;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #e9d8c8;
    margin-bottom: 15px;
}

.price-item {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 8px;
}

.price-item:last-child {
    margin-bottom: 0;
}

.price-item label {
    font-weight: 600;
    color: #666;
    font-size: 13px;
    min-width: 120px;
}

.price-item span {
    font-weight: bold;
    color: #b68f82;
}

.preview-card {
    background: white;
    padding: 15px;
    border-radius: 8px;
    border: 2px solid #b68f82;
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
    font-size: 14px;
    font-weight: bold;
    color: #b68f82;
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
function loadProductData() {
    const productSelect = document.getElementById('product_id');
    const selectedOption = productSelect.options[productSelect.selectedIndex];
    
    if (selectedOption.value) {
        const price = selectedOption.getAttribute('data-price');
        const cost = selectedOption.getAttribute('data-cost');
        const stock = selectedOption.getAttribute('data-stock');
        
        document.getElementById('unitPrice').textContent = 'Rp ' + parseFloat(price).toLocaleString('id-ID');
        document.getElementById('unitCost').textContent = 'Rp ' + parseFloat(cost).toLocaleString('id-ID');
        document.getElementById('availableStock').textContent = stock;
        
        document.getElementById('stockInfo').textContent = 'Stok tersedia: ' + stock + ' unit';
        document.getElementById('stockInfo').style.color = '#28a745';
        
        // Reset quantity and recalculate
        document.getElementById('quantity_sold').value = '';
        calculateRevenue();
    } else {
        resetProductData();
    }
}

function calculateRevenue() {
    const productSelect = document.getElementById('product_id');
    const quantityInput = document.getElementById('quantity_sold');
    const selectedOption = productSelect.options[productSelect.selectedIndex];
    
    if (selectedOption.value && quantityInput.value) {
        const price = parseFloat(selectedOption.getAttribute('data-price'));
        const cost = parseFloat(selectedOption.getAttribute('data-cost'));
        const stock = parseInt(selectedOption.getAttribute('data-stock'));
        const quantity = parseInt(quantityInput.value);
        
        const totalRevenue = price * quantity;
        const totalCost = cost * quantity;
        const totalProfit = totalRevenue - totalCost;
        const margin = totalRevenue > 0 ? (totalProfit / totalRevenue) * 100 : 0;
        
        // Update display
        document.getElementById('totalRevenue').textContent = 'Rp ' + totalRevenue.toLocaleString('id-ID');
        document.getElementById('totalCost').textContent = 'Rp ' + totalCost.toLocaleString('id-ID');
        document.getElementById('totalProfit').textContent = 'Rp ' + totalProfit.toLocaleString('id-ID');
        document.getElementById('totalMargin').textContent = margin.toFixed(1) + '%';
        
        // Color code margin
        if (margin >= 50) {
            document.getElementById('totalMargin').style.color = '#155724';
        } else if (margin >= 30) {
            document.getElementById('totalMargin').style.color = '#856404';
        } else if (margin > 0) {
            document.getElementById('totalMargin').style.color = '#721c24';
        } else {
            document.getElementById('totalMargin').style.color = '#dc3545';
        }
        
        // Check stock availability
        if (quantity > stock) {
            document.getElementById('stockInfo').textContent = '⚠️ Stok tidak mencukupi! Stok tersedia: ' + stock;
            document.getElementById('stockInfo').style.color = '#dc3545';
        } else {
            document.getElementById('stockInfo').textContent = 'Stok tersedia: ' + stock + ' unit';
            document.getElementById('stockInfo').style.color = '#28a745';
        }
    } else {
        resetRevenueDisplay();
    }
}

function resetProductData() {
    document.getElementById('unitPrice').textContent = 'Rp 0';
    document.getElementById('unitCost').textContent = 'Rp 0';
    document.getElementById('availableStock').textContent = '0';
    document.getElementById('stockInfo').textContent = '';
    resetRevenueDisplay();
}

function resetRevenueDisplay() {
    document.getElementById('totalRevenue').textContent = 'Rp 0';
    document.getElementById('totalCost').textContent = 'Rp 0';
    document.getElementById('totalProfit').textContent = 'Rp 0';
    document.getElementById('totalMargin').textContent = '0%';
    document.getElementById('totalMargin').style.color = '#666';
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Load product data if product is already selected (from old input)
    const productSelect = document.getElementById('product_id');
    if (productSelect.value) {
        loadProductData();
    }
    
    // Calculate revenue if quantity is already filled (from old input)
    const quantityInput = document.getElementById('quantity_sold');
    if (quantityInput.value) {
        calculateRevenue();
    }
});
</script>
@endsection