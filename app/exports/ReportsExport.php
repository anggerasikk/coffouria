<?php

namespace App\Exports;

use App\Models\DailyReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class ReportsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        return DailyReport::whereYear('report_date', $this->year)
                    ->whereMonth('report_date', $this->month)
                    ->orderBy('report_date', 'asc')
                    ->get();
    }

    public function headings(): array
    {
        $monthName = Carbon::create()->month($this->month)->locale('id')->monthName;
        
        return [
            "LAPORAN PENJUALAN BULAN {$monthName} {$this->year}",
            '',
            [
                'Tanggal',
                'Nama Produk',
                'Kategori',
                'Kode Produk',
                'Quantity Terjual',
                'Pendapatan (Rp)',
                'Biaya Produksi (Rp)',
                'Profit (Rp)',
                'Margin (%)',
                'Catatan'
            ]
        ];
    }

    public function map($report): array
    {
        $profit = $report->revenue - $report->cost;
        
        return [
            Carbon::parse($report->report_date)->format('d/m/Y'),
            $report->product_name,
            $report->category,
            $report->product_code,
            $report->quantity_sold,
            number_format($report->revenue, 0, ',', '.'),
            number_format($report->cost, 0, ',', '.'),
            number_format($profit, 0, ',', '.'),
            number_format($report->margin, 1),
            $report->notes ?? '-'
        ];
    }
}