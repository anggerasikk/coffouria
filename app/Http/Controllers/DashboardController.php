<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DailyReport;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard
     */
    public function index(Request $request)
    {
        // Ambil filter dari request
        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));
        
        // Get data dari database
        $summary = $this->getMonthlySummary($month, $year);
        
        $data = [
            'totalProducts' => $summary['totalProducts'],
            'totalRevenue' => $summary['totalRevenue'],
            'dailyAverage' => $summary['dailyAverage'],
            'bestSeller' => $summary['bestSeller'],
            'currentMonth' => $month,
            'currentYear' => $year,
            'monthName' => $this->getMonthName($month)
        ];

        return view('dashboard', $data);
    }

    /**
     * Get month name in Indonesian
     */
    private function getMonthName($month)
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return $months[$month] ?? 'Unknown';
    }

    /**
     * Get monthly summary data from database
     */
    private function getMonthlySummary($month, $year)
    {
        $data = DailyReport::whereYear('report_date', $year)
            ->whereMonth('report_date', $month)
            ->select(
                DB::raw('SUM(quantity_sold) as total_products'),
                DB::raw('SUM(revenue) as total_revenue'),
                DB::raw('AVG(revenue) as daily_average')
            )
            ->first();

        $bestSeller = DailyReport::whereYear('report_date', $year)
            ->whereMonth('report_date', $month)
            ->select('product_name', DB::raw('SUM(quantity_sold) as total_sold'))
            ->groupBy('product_name')
            ->orderByDesc('total_sold')
            ->first();

        return [
            'totalProducts' => $data->total_products ?? 0,
            'totalRevenue' => $data->total_revenue ?? 0,
            'dailyAverage' => $data->daily_average ?? 0,
            'bestSeller' => $bestSeller->product_name ?? '-'
        ];
    }
}