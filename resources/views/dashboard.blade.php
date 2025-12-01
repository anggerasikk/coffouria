@extends('layouts.app')

@section('title', 'DASHBOARD')

@section('content')
<div class="dashboard-content">
    <h3 class="section-title">LAPORAN PENJUALAN PERIODE 
        {{ $monthName }} {{ $currentYear }}
    </h3>
    
    <!-- Filter Controls -->
    <form method="GET" action="{{ route('dashboard') }}" class="filter-form">
        <div class="filter-controls">
            <div class="filter-group">
                <label>Bulan:</label>
                <select name="month" class="form-select">
                    @php
                        $months = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                    @endphp
                    @foreach($months as $key => $name)
                        <option value="{{ $key }}" {{ $currentMonth == $key ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>Tahun:</label>
                <select name="year" class="form-select">
                    @for($i = 2023; $i <= 2027; $i++)
                        <option value="{{ $i }}" {{ $currentYear == $i ? 'selected' : '' }}>{{ $i }}</option>
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
            <div class="card-icon">📦</div>
            <div class="card-content">
                <h3>Total Produk Terjual</h3>
                <div class="value">{{ $totalProducts }}</div>
                <div class="unit">Pcs</div>
            </div>
        </div>
        <div class="summary-card">
            <div class="card-icon">💰</div>
            <div class="card-content">
                <h3>Total Pendapatan</h3>
                <div class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                <div class="unit">Bulan Ini</div>
            </div>
        </div>
        <div class="summary-card">
            <div class="card-icon">📊</div>
            <div class="card-content">
                <h3>Rata-rata Harian</h3>
                <div class="value">Rp {{ number_format($dailyAverage, 0, ',', '.') }}</div>
                <div class="unit">Per Hari</div>
            </div>
        </div>
        <div class="summary-card">
            <div class="card-icon">🏆</div>
            <div class="card-content">
                <h3>Produk Terlaris</h3>
                <div class="value">{{ $bestSeller }}</div>
                <div class="unit">Top Seller</div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="quick-stats">
        <div class="stat-item">
            <div class="stat-icon">📈</div>
            <div class="stat-info">
                <div class="stat-label">Trend Penjualan</div>
                <div class="stat-value">+15%</div>
                <div class="stat-desc">vs bulan lalu</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">⏱️</div>
            <div class="stat-info">
                <div class="stat-label">Waktu Operasional</div>
                <div class="stat-value">30 Hari</div>
                <div class="stat-desc">Bulan {{ $monthName }}</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon">🎯</div>
            <div class="stat-info">
                <div class="stat-label">Target Bulanan</div>
                <div class="stat-value">Rp 25.000.000</div>
                <div class="stat-desc">{{ number_format(($totalRevenue / 25000000) * 100, 0) }}% Tercapai</div>
            </div>
        </div>
    </div>
</div>

<style>
.dashboard-content {
    padding: 0;
}

.section-title {
    color: #b68f82;
    font-size: 24px;
    margin-bottom: 25px;
    text-align: center;
    font-weight: bold;
}

.filter-form {
    margin-bottom: 30px;
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
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

.summary-card {
    background: linear-gradient(135deg, #b68f82, #9a7568);
    color: white;
    padding: 25px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    gap: 20px;
    box-shadow: 0 5px 15px rgba(182, 143, 130, 0.3);
    transition: transform 0.3s ease;
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.summary-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(182, 143, 130, 0.4);
}

.card-icon {
    font-size: 50px;
    opacity: 0.9;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    padding: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 80px;
    height: 80px;
}

.card-content {
    flex: 1;
}

.card-content h3 {
    font-size: 14px;
    margin-bottom: 8px;
    opacity: 0.9;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card-content .value {
    font-size: 28px;
    font-weight: bold;
    margin-bottom: 5px;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
}

.card-content .unit {
    font-size: 12px;
    opacity: 0.8;
    font-weight: 500;
}

.quick-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

.stat-item {
    background: white;
    padding: 20px;
    border-radius: 12px;
    border: 2px solid #e9d8c8;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
    border-color: #b68f82;
}

.stat-icon {
    font-size: 32px;
    background: #f8f4e9;
    border-radius: 10px;
    padding: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 60px;
    height: 60px;
}

.stat-info {
    flex: 1;
}

.stat-label {
    font-size: 12px;
    color: #666;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 5px;
}

.stat-value {
    font-size: 18px;
    font-weight: bold;
    color: #b68f82;
    margin-bottom: 3px;
}

.stat-desc {
    font-size: 11px;
    color: #888;
    font-weight: 500;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .summary-cards {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .section-title {
        font-size: 20px;
        margin-bottom: 20px;
    }
    
    .filter-controls {
        flex-direction: column;
        align-items: stretch;
        gap: 15px;
    }
    
    .filter-group {
        width: 100%;
    }
    
    .form-select {
        min-width: auto;
        width: 100%;
    }
    
    .summary-cards {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .summary-card {
        padding: 20px;
        gap: 15px;
    }
    
    .card-icon {
        font-size: 40px;
        min-width: 70px;
        height: 70px;
        padding: 12px;
    }
    
    .card-content .value {
        font-size: 24px;
    }
    
    .quick-stats {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .stat-item {
        padding: 15px;
    }
    
    .stat-icon {
        font-size: 28px;
        min-width: 50px;
        height: 50px;
        padding: 10px;
    }
    
    .stat-value {
        font-size: 16px;
    }
}

@media (max-width: 480px) {
    .summary-card {
        flex-direction: column;
        text-align: center;
        gap: 15px;
    }
    
    .card-icon {
        align-self: center;
    }
    
    .stat-item {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .stat-icon {
        align-self: center;
    }
}
</style>

<script>
// Add some interactive effects
document.addEventListener('DOMContentLoaded', function() {
    // Add loading animation to summary cards
    const summaryCards = document.querySelectorAll('.summary-card');
    summaryCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Add progress bar for target achievement
    const targetProgress = document.querySelector('.stat-desc');
    if (targetProgress) {
        const progressText = targetProgress.textContent;
        const progressMatch = progressText.match(/(\d+)%/);
        if (progressMatch) {
            const progress = progressMatch[1];
            targetProgress.innerHTML = `<div class="progress-bar">
                <div class="progress-fill" style="width: ${progress}%"></div>
                <span>${progress}% Tercapai</span>
            </div>`;
        }
    }
});

// Add progress bar styles
const style = document.createElement('style');
style.textContent = `
    .progress-bar {
        width: 100%;
        height: 6px;
        background: #e9d8c8;
        border-radius: 3px;
        margin-top: 5px;
        position: relative;
        overflow: hidden;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #b68f82, #9a7568);
        border-radius: 3px;
        transition: width 0.5s ease;
    }
    
    .progress-bar span {
        position: absolute;
        top: -18px;
        left: 0;
        font-size: 10px;
        color: #666;
        font-weight: 500;
    }
`;
document.head.appendChild(style);
</script>
@endsection