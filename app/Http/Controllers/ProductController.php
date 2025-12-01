<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $category = $request->get('category');
        
        $products = Product::when($search, function($query) use ($search) {
            return $query->where('name', 'like', "%{$search}%")
                        ->orWhere('product_code', 'like', "%{$search}%");
        })
        ->when($category, function($query) use ($category) {
            return $query->where('category', $category);
        })
        ->orderBy('name')
        ->get();

        $categories = Product::distinct()->pluck('category');

        return view('products.index', compact('products', 'search', 'categories', 'category'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'cost' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'min_stock' => 'required|integer|min:0',
                'unit' => 'required|string|max:20',
                'is_active' => 'sometimes|boolean'
            ]);

            // Kode produk akan digenerate otomatis oleh model
            $validated['is_active'] = $request->has('is_active');

            Product::create($validated);

            return redirect()->route('products.index')
                            ->with('success', 'Produk berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Gagal menambahkan produk: ' . $e->getMessage())
                            ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'cost' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'min_stock' => 'required|integer|min:0',
                'unit' => 'required|string|max:20',
                'is_active' => 'sometimes|boolean'
            ]);

            $validated['is_active'] = $request->has('is_active');

            // Update status aktif berdasarkan stok
            if ($validated['stock'] <= 0) {
                $validated['is_active'] = false;
            }

            $product->update($validated);

            return redirect()->route('products.index')
                            ->with('success', 'Produk berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Gagal memperbarui produk: ' . $e->getMessage())
                            ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();

            return redirect()->route('products.index')
                            ->with('success', 'Produk berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    /**
     * Update stock produk
     */
    public function updateStock(Request $request, Product $product)
    {
        try {
            $validated = $request->validate([
                'stock' => 'required|integer|min:0'
            ]);

            $product->update($validated);

            // Update status aktif berdasarkan stok
            if ($product->stock <= 0) {
                $product->update(['is_active' => false]);
            }

            return redirect()->route('products.index')
                            ->with('success', 'Stok produk berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Gagal memperbarui stok: ' . $e->getMessage());
        }
    }
}