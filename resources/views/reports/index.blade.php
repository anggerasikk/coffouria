@extends('layouts.app')

@section('title', 'MANAJEMEN LAPORAN')

@section('content')
<div class="reports-container">
    <div class="page-header">
        <h3>📊 MANAJEMEN LAPORAN HARIAN</h3>
        <div class="header-actions">
            <!-- Tombol Export CSV yang langsung download -->
            <a href="{{ route('reports.export', ['month' => $month, 'year' => $year]) }}" 
               class="btn-download-csv" 
               title="Download data dalam format CSV">
               ⬇️ Download CSV
            </a>
            
            <a href="{{ route('reports.create') }}" class="btn-primary">
                ➕ Tambah Laporan Baru
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Filter Controls -->
    <form method="GET" action="{{ route('reports.index') }}" class="filter-form">
        <div class="filter-controls">
            <div class="filter-group">
                <label>Bulan:</label>
                <select name="month" class="form-select">
                    @php
                        $monthNames = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                    @endphp
                    @foreach($monthNames as $key => $name)
                        <option value="{{ $key }}" {{ $month == $key ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>Tahun:</label>
                <select name="year" class="form-select">
                    @for($i = 2023; $i <= 2027; $i++)
                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="filter-group">
                <label>&nbsp;</label>
                <button type="submit" class="btn-apply">🔍 Terapkan Filter</button>
            </div>
        </div>
    </form>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <h3>Total Laporan</h3>
            <div class="value">{{ $reports->count() }}</div>
        </div>
        <div class="summary-card">
            <h3>Total Produk Terjual</h3>
            <div class="value">{{ $summary['totalProducts'] }} Pcs</div>
        </div>
        <div class="summary-card">
            <h3>Total Pendapatan</h3>
            <div class="value">Rp {{ number_format($summary['totalRevenue'], 0, ',', '.') }}</div>
        </div>
        <div class="summary-card">
            <h3>Produk Terlaris</h3>
            <div class="value">{{ $summary['bestSeller'] }}</div>
        </div>
    </div>

    <!-- Reports Table -->
    <div class="table-container">
        <div class="table-header">
            <h4>📋 Data Laporan Harian</h4>
            <div class="table-actions">
                <span class="total-records">Total: {{ $reports->count() }} laporan</span>
                <!-- Tombol Download di dalam tabel -->
                <a href="{{ route('reports.export', ['month' => $month, 'year' => $year]) }}" 
                   class="btn-download-small">
                   ⬇️ Download Data
                </a>
            </div>
        </div>

        <table class="reports-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Kode Produk</th>
                    <th>Terjual</th>
                    <th>Pendapatan</th>
                    <th>Margin</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($report->report_date)->format('d/m/Y') }}</td>
                        <td class="product-name">{{ $report->product_name }}</td>
                        <td><span class="category-badge">{{ $report->category }}</span></td>
                        <td class="product-id">{{ $report->product_code }}</td>
                        <td class="quantity">{{ $report->quantity_sold }} pcs</td>
                        <td class="revenue">Rp {{ number_format($report->revenue, 0, ',', '.') }}</td>
                        <td class="margin">
                            <span class="margin-badge {{ $report->margin >= 50 ? 'high' : ($report->margin >= 30 ? 'medium' : 'low') }}">
                                {{ number_format($report->margin, 1) }}%
                            </span>
                        </td>
                        <td class="actions">
                            <a href="{{ route('reports.edit', $report->id) }}" class="btn-edit" title="Edit">
                                ✏️ Edit
                            </a>
                            <form action="{{ route('reports.destroy', $report->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('Hapus laporan ini?')" title="Hapus">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="no-data">
                            <div class="empty-state">
                                <span class="icon">📊</span>
                                <h4>Belum ada laporan</h4>
                                <p>Mulai dengan menambahkan laporan harian pertama Anda</p>
                                <a href="{{ route('reports.create') }}" class="btn-primary">Tambah Laporan Pertama</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
/* STYLE UNTUK TOMBOL DOWNLOAD */
.btn-download-csv {
    padding: 12px 25px;
    background: #28a745;
    color: white;
    text-decoration: none;
    border-radius: 10px;
    font-weight: bold;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    display: inline-block;
    text-align: center;
    margin-right: 10px;
}

.btn-download-csv:hover {
    background: #218838;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
}

.btn-download-small {
    padding: 8px 15px;
    background: #28a745;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
    font-size: 13px;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    margin-left: 10px;
}

.btn-download-small:hover {
    background: #218838;
    transform: translateY(-1px);
}

.header-actions {
    display: flex;
    align-items: center;
}

/* STYLE LAIN YANG SUDAH ADA... */
.reports-container {
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

.filter-form {
    margin-bottom: 25px;
}

.filter-controls {
    display: flex;
    gap: 20px;
    align-items: end;
    flex-wrap: wrap;
    background: #f8f4e9;
    padding: 20px;
    border-radius: 15px;
    border: 2px solid #b68f82;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.filter-group label {
    font-size: 14px;
    color: #b68f82;
    font-weight: bold;
}

.form-select {
    padding: 12px 15px;
    border: 2px solid #b68f82;
    border-radius: 10px;
    min-width: 180px;
    background: white;
    color: #5a4a42;
    font-size: 14px;
}

.btn-apply {
    padding: 12px 25px;
    background: #b68f82;
    color: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s ease;
}

.btn-apply:hover {
    background: #9a7568;
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

.table-actions {
    display: flex;
    align-items: center;
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

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.reports-table {
    width: 100%;
    border-collapse: collapse;
}

.reports-table th {
    background: #b68f82;
    color: white;
    padding: 15px 12px;
    text-align: left;
    font-weight: 600;
    font-size: 14px;
}

.reports-table td {
    padding: 12px;
    border-bottom: 1px solid #f0f0f0;
}

.reports-table tr:hover {
    background: #f8f4e9;
}

.product-name {
    font-weight: 600;
    color: #b68f82;
}

.category-badge {
    padding: 4px 8px;
    background: #e9d8c8;
    color: #8B4513;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
}

.product-id {
    font-family: monospace;
    background: #f5f5f5;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
}

.quantity, .revenue {
    font-weight: 600;
    text-align: right;
}

.margin-badge {
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}

.margin-badge.high {
    background: #d4edda;
    color: #155724;
}

.margin-badge.medium {
    background: #fff3cd;
    color: #856404;
}

.margin-badge.low {
    background: #f8d7da;
    color: #721c24;
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
</style>
@endsection