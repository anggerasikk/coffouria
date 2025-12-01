<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_date',
        'product_name',
        'category',
        'product_id',
        'quantity_sold',
        'revenue',
        'cost',
        'margin',
        'notes'
    ];

    protected $casts = [
        'report_date' => 'date',
        'revenue' => 'decimal:2',
        'cost' => 'decimal:2',
        'margin' => 'decimal:2'
    ];

    /**
     * Scope untuk filter bulan
     */
    public function scopeForMonth($query, $month, $year)
    {
        return $query->whereYear('report_date', $year)
                    ->whereMonth('report_date', $month);
    }

    /**
     * Scope untuk filter tanggal
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('report_date', $date);
    }
}