<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DailyReport;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exports\ReportsExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $month = $request->get('month', date('n'));
    $year = $request->get('year', date('Y'));
    
    // Get daily reports for the selected month
    $reports = DailyReport::forMonth($month, $year)
                ->orderBy('report_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
    
    // Get summary
    $summary = $this->getMonthlySummary($month, $year);
    
    // Manual month names untuk view
    $monthNames = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    
    return view('reports.index', compact('reports', 'summary', 'month', 'year', 'monthNames'));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('reports.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'report_date' => 'required|date',
                'product_id' => 'required|exists:products,id',
                'quantity_sold' => 'required|integer|min:1',
                'notes' => 'nullable|string|max:500'
            ]);

            // Get product data
            $product = Product::findOrFail($validated['product_id']);

            // Check if product has enough stock
            if ($product->stock < $validated['quantity_sold']) {
                return redirect()->back()
                                ->with('error', 'Stok produk tidak mencukupi! Stok tersedia: ' . $product->stock)
                                ->withInput();
            }

            // Calculate revenue and cost based on product data
            $validated['product_name'] = $product->name;
            $validated['category'] = $product->category;
            $validated['product_code'] = $product->product_code;
            $validated['revenue'] = $product->price * $validated['quantity_sold'];
            $validated['cost'] = $product->cost * $validated['quantity_sold'];
            
            // Calculate margin
            if ($validated['revenue'] > 0) {
                $validated['margin'] = (($validated['revenue'] - $validated['cost']) / $validated['revenue']) * 100;
            } else {
                $validated['margin'] = 0;
            }

            // Create report
            DailyReport::create($validated);

            // Update product stock
            $newStock = $product->stock - $validated['quantity_sold'];
            $product->update([
                'stock' => $newStock,
                'is_active' => $newStock > 0 // Deactivate if stock is 0
            ]);

            return redirect()->route('reports.index')
                            ->with('success', 'Laporan penjualan berhasil ditambahkan dan stok produk diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Gagal menambahkan laporan: ' . $e->getMessage())
                            ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DailyReport $report)
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('reports.edit', compact('report', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DailyReport $report)
    {
        try {
            $validated = $request->validate([
                'report_date' => 'required|date',
                'product_id' => 'required|exists:products,id',
                'quantity_sold' => 'required|integer|min:1',
                'notes' => 'nullable|string|max:500'
            ]);

            // Get product data
            $product = Product::findOrFail($validated['product_id']);

            // Calculate the difference in quantity for stock adjustment
            $quantityDifference = $validated['quantity_sold'] - $report->quantity_sold;

            // Check if product has enough stock for the increase
            if ($quantityDifference > 0 && $product->stock < $quantityDifference) {
                return redirect()->back()
                                ->with('error', 'Stok produk tidak mencukupi untuk penyesuaian ini! Stok tersedia: ' . $product->stock)
                                ->withInput();
            }

            // Calculate revenue and cost based on product data
            $validated['product_name'] = $product->name;
            $validated['category'] = $product->category;
            $validated['product_code'] = $product->product_code;
            $validated['revenue'] = $product->price * $validated['quantity_sold'];
            $validated['cost'] = $product->cost * $validated['quantity_sold'];
            
            // Calculate margin
            if ($validated['revenue'] > 0) {
                $validated['margin'] = (($validated['revenue'] - $validated['cost']) / $validated['revenue']) * 100;
            } else {
                $validated['margin'] = 0;
            }

            // Update report
            $report->update($validated);

            // Update product stock
            $newStock = $product->stock - $quantityDifference;
            $product->update([
                'stock' => $newStock,
                'is_active' => $newStock > 0 // Deactivate if stock is 0
            ]);

            return redirect()->route('reports.index')
                            ->with('success', 'Laporan berhasil diperbarui dan stok produk disesuaikan!');

        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Gagal memperbarui laporan: ' . $e->getMessage())
                            ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailyReport $report)
    {
        try {
            // Restore product stock before deleting report
            $product = Product::where('product_code', $report->product_code)->first();
            if ($product) {
                $newStock = $product->stock + $report->quantity_sold;
                $product->update([
                    'stock' => $newStock,
                    'is_active' => true // Reactivate if stock becomes positive
                ]);
            }

            $report->delete();

            return redirect()->route('reports.index')
                            ->with('success', 'Laporan berhasil dihapus dan stok produk dikembalikan!');

        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Gagal menghapus laporan: ' . $e->getMessage());
        }
    }

    /**
     * Get monthly summary data
     */
    private function getMonthlySummary($month, $year)
    {
        $data = DailyReport::forMonth($month, $year)
            ->select(
                DB::raw('SUM(quantity_sold) as total_products'),
                DB::raw('SUM(revenue) as total_revenue'),
                DB::raw('AVG(revenue) as daily_average')
            )
            ->first();

        $bestSeller = DailyReport::forMonth($month, $year)
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

    /**
     * Get product data for AJAX request
     */
    public function getProductData($id)
    {
        $product = Product::findOrFail($id);
        return response()->json([
            'name' => $product->name,
            'category' => $product->category,
            'product_code' => $product->product_code,
            'price' => $product->price,
            'cost' => $product->cost,
            'stock' => $product->stock
        ]);
    }
   /**
 * Export laporan langsung download
 */
public function export(Request $request)
{
    try {
        $month = $request->get('month', date('n'));
        $year = $request->get('year', date('Y'));
        
        // PERBAIKAN: Gunakan cara yang benar untuk mendapatkan nama bulan
        $monthName = Carbon::createFromDate($year, $month, 1)->locale('id')->monthName;
        
        // Nama file
        $filename = "Laporan_Penjualan_{$monthName}_{$year}.csv";
        
        // Header untuk download
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        // Data dari database
        $reports = DailyReport::whereYear('report_date', $year)
                    ->whereMonth('report_date', $month)
                    ->orderBy('report_date', 'asc')
                    ->get();
        
        $callback = function() use ($reports, $monthName, $year) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fwrite($file, "\xEF\xBB\xBF"); // UTF-8 BOM
            
            // Judul
            fputcsv($file, ["LAPORAN PENJUALAN BULAN {$monthName} {$year}"]);
            fputcsv($file, ["COFFOURIA - Coffee Shop Management System"]);
            fputcsv($file, []); // Baris kosong
            
            // Header kolom
            fputcsv($file, [
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
            ]);
            
            // Data
            foreach ($reports as $report) {
                $profit = $report->revenue - $report->cost;
                
                fputcsv($file, [
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
                ]);
            }
            
            // Summary
            fputcsv($file, []);
            fputcsv($file, ["TOTAL:", "", "", "", 
                $reports->sum('quantity_sold') . ' pcs',
                'Rp ' . number_format($reports->sum('revenue'), 0, ',', '.'),
                'Rp ' . number_format($reports->sum('cost'), 0, ',', '.'),
                'Rp ' . number_format($reports->sum('revenue') - $reports->sum('cost'), 0, ',', '.'),
                '',
                '']);
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
        
    } catch (\Exception $e) {
        // Fallback: jika ada error, gunakan angka bulan saja
        return $this->exportFallback($request);
    }
}

/**
 * Fallback export jika ada error dengan Carbon
 */
private function exportFallback(Request $request)
{
    $month = $request->get('month', date('n'));
    $year = $request->get('year', date('Y'));
    
    // Array nama bulan manual
    $monthNames = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    
    $monthName = $monthNames[$month] ?? "Bulan_$month";
    $filename = "Laporan_Penjualan_{$monthName}_{$year}.csv";
    
    $headers = [
        'Content-Type' => 'text/csv; charset=utf-8',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
        'Pragma' => 'no-cache',
        'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        'Expires' => '0'
    ];
    
    // Data dari database
    $reports = DailyReport::whereYear('report_date', $year)
                ->whereMonth('report_date', $month)
                ->orderBy('report_date', 'asc')
                ->get();
    
    $callback = function() use ($reports, $monthName, $year) {
        $file = fopen('php://output', 'w');
        
        // Header CSV
        fwrite($file, "\xEF\xBB\xBF"); // UTF-8 BOM
        
        // Judul
        fputcsv($file, ["LAPORAN PENJUALAN BULAN {$monthName} {$year}"]);
        fputcsv($file, ["COFFOURIA - Coffee Shop Management System"]);
        fputcsv($file, []); // Baris kosong
        
        // Header kolom
        fputcsv($file, [
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
        ]);
        
        // Data
        foreach ($reports as $report) {
            $profit = $report->revenue - $report->cost;
            
            // Format tanggal manual tanpa Carbon
            $date = date('d/m/Y', strtotime($report->report_date));
            
            fputcsv($file, [
                $date,
                $report->product_name,
                $report->category,
                $report->product_code,
                $report->quantity_sold,
                number_format($report->revenue, 0, ',', '.'),
                number_format($report->cost, 0, ',', '.'),
                number_format($profit, 0, ',', '.'),
                number_format($report->margin, 1),
                $report->notes ?? '-'
            ]);
        }
        
        // Summary
        fputcsv($file, []);
        fputcsv($file, ["TOTAL:", "", "", "", 
            $reports->sum('quantity_sold') . ' pcs',
            'Rp ' . number_format($reports->sum('revenue'), 0, ',', '.'),
            'Rp ' . number_format($reports->sum('cost'), 0, ',', '.'),
            'Rp ' . number_format($reports->sum('revenue') - $reports->sum('cost'), 0, ',', '.'),
            '',
            '']);
        
        fclose($file);
    };
    
    return response()->stream($callback, 200, $headers);
}    
}   