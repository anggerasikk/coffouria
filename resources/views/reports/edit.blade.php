@extends('layouts.app')

@section('title', 'EDIT LAPORAN')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h3>✏️ EDIT LAPORAN HARIAN</h3>
        <a href="{{ route('reports.index') }}" class="btn-back">⬅ Kembali ke Daftar Laporan</a>
    </div>

    <form method="POST" action="{{ route('reports.update', $report->id) }}" class="report-form">
        @csrf
        @method('PUT')
        
        <div class="form-layout">
            <!-- Kolom Kiri -->
            <div class="form-column">
                <div class="form-section">
                    <h4>📊 Informasi Penjualan</h4>
                    
                    <!-- Tanggal Laporan -->
                    <div class="form-group">
                        <label for="report_date">📅 Tanggal Laporan *</label>
                        <input type="date" id="report_date" name="report_date" 
                               value="{{ old('report_date', $report->report_date->format('Y-m-d')) }}" 
                               class="form-input @error('report_date') error @enderror" required>
                        @error('report_date')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Nama Produk -->
                    <div class="form-group">
                        <label for="product_name">📦 Nama Produk *</label>
                        <input type="text" id="product_name" name="product_name" 
                               value="{{ old('product_name', $report->product_name) }}" 
                               class="form-input @error('product_name') error @enderror" required>
                        @error('product_name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div class="form-group">
                        <label for="category">🏷️ Kategori *</label>
                        <select id="category" name="category" class="form-select @error('category') error @enderror" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Hot Coffee" {{ old('category', $report->category) == 'Hot Coffee' ? 'selected' : '' }}>Hot Coffee</option>
                            <option value="Cold Coffee" {{ old('category', $report->category) == 'Cold Coffee' ? 'selected' : '' }}>Cold Coffee</option>
                            <option value="Tea" {{ old('category', $report->category) == 'Tea' ? 'selected' : '' }}>Tea</option>
                            <option value="Snack" {{ old('category', $report->category) == 'Snack' ? 'selected' : '' }}>Snack</option>
                            <option value="Dessert" {{ old('category', $report->category) == 'Dessert' ? 'selected' : '' }}>Dessert</option>
                            <option value="Other" {{ old('category', $report->category) == 'Other' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('category')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- ID Produk -->
                    <div class="form-group">
                        <label for="product_id">🆔 ID Produk *</label>
                        <input type="text" id="product_id" name="product_id" 
                               value="{{ old('product_id', $report->product_id) }}" 
                               class="form-input @error('product_id') error @enderror" required>
                        @error('product_id')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="form-column">
                <div class="form-section">
                    <h4>💰 Data Keuangan</h4>

                    <!-- Quantity Sold -->
                    <div class="form-group">
                        <label for="quantity_sold">📊 Quantity Terjual *</label>
                        <input type="number" id="quantity_sold" name="quantity_sold" 
                               value="{{ old('quantity_sold', $report->quantity_sold) }}" 
                               class="form-input @error('quantity_sold') error @enderror" min="0" required>
                        @error('quantity_sold')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Revenue -->
                    <div class="form-group">
                        <label for="revenue">💰 Pendapatan (Rp) *</label>
                        <input type="number" id="revenue" name="revenue" 
                               value="{{ old('revenue', $report->revenue) }}" 
                               class="form-input @error('revenue') error @enderror" min="0" step="0.01" required oninput="calculateMargin()">
                        @error('revenue')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Cost -->
                    <div class="form-group">
                        <label for="cost">💸 Biaya Produksi (Rp) *</label>
                        <input type="number" id="cost" name="cost" 
                               value="{{ old('cost', $report->cost) }}" 
                               class="form-input @error('cost') error @enderror" min="0" step="0.01" required oninput="calculateMargin()">
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
                                <span class="value" id="marginPreview">{{ number_format($report->margin, 1) }}%</span>
                            </div>
                            <div class="preview-item">
                                <span class="label">Profit:</span>
                                <span class="value" id="profitPreview">Rp {{ number_format($report->revenue - $report->cost, 0, ',', '.') }}</span>
                            </div>
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
                          rows="4" placeholder="Tambahkan catatan atau keterangan tambahan...">{{ old('notes', $report->notes) }}</textarea>
                @error('notes')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <a href="{{ route('reports.index') }}" class="btn-cancel">❌ Batal</a>
            <button type="submit" class="btn-submit">💾 Update Laporan</button>
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

.report-form {
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

.form-section.full-width {
    grid-column: 1 / -1;
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
    
    .report-form {
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
    const revenue = parseFloat(document.getElementById('revenue').value) || 0;
    const cost = parseFloat(document.getElementById('cost').value) || 0;
    
    let margin = 0;
    let profit = 0;
    
    if (revenue > 0) {
        profit = revenue - cost;
        margin = (profit / revenue) * 100;
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