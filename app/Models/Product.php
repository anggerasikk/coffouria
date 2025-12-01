<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'product_code',
        'description',
        'price',
        'cost',
        'stock',
        'min_stock',
        'unit',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->product_code)) {
                $product->product_code = static::generateProductCode($product->category);
            }
        });

        static::updating(function ($product) {
            // Auto deactivate if stock is 0 or below minimum
            if ($product->stock <= 0) {
                $product->is_active = false;
            }
        });
    }

    /**
     * Generate automatic product code
     */
    protected static function generateProductCode($category)
    {
        $prefix = static::getCategoryPrefix($category);
        $lastProduct = static::where('product_code', 'like', $prefix . '%')
                            ->orderBy('product_code', 'desc')
                            ->first();

        if ($lastProduct) {
            $lastNumber = intval(substr($lastProduct->product_code, 3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get category prefix for product code
     */
    protected static function getCategoryPrefix($category)
    {
        $prefixes = [
            'Hot Coffee' => 'HOT',
            'Cold Coffee' => 'CLD',
            'Tea' => 'TEA',
            'Snack' => 'SNK',
            'Dessert' => 'DSR',
            'Bakery' => 'BKY',
            'Other' => 'OTH'
        ];

        return $prefixes[$category] ?? 'PRO';
    }

    /**
     * Scope untuk produk aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk mencari produk
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('product_code', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
    }

    /**
     * Cek apakah stok rendah
     */
    public function getIsLowStockAttribute()
    {
        return $this->stock <= $this->min_stock;
    }

    /**
     * Hitung margin
     */
    public function getMarginAttribute()
    {
        if ($this->price > 0) {
            return (($this->price - $this->cost) / $this->price) * 100;
        }
        return 0;
    }

    /**
     * Update stock and auto manage active status
     */
    public function updateStock($newStock)
    {
        $this->stock = $newStock;
        $this->is_active = $newStock > 0;
        $this->save();
    }
}