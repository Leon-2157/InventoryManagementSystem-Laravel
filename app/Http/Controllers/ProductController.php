<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with(['category', 'suppliers']);

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->query('category_id'));
        }

        $sortableColumns = ['name', 'sku', 'price', 'stock', 'created_at'];
        $sort = in_array($request->query('sort'), $sortableColumns, true) ? $request->query('sort') : 'name';
        $dir = strtolower($request->query('dir', 'asc')) === 'desc' ? 'desc' : 'asc';

        $products = $query->orderBy($sort, $dir)->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories', 'sort', 'dir'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $product = Product::create($request->only([
            'category_id',
            'sku',
            'name',
            'price',
            'stock',
            'minimum_stock',
        ]));

        $this->syncSuppliers($product, $request->input('supplier_ids'), $request->input('supplier_prices'));

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product): View
    {
        $product->load([
            'category',
            'suppliers',
            'stockMovements' => fn ($q) => $q->with('user.profile')->latest()->limit(10),
        ]);

        return view('products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        $product->load('suppliers');
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->only([
            'category_id',
            'sku',
            'name',
            'price',
            'minimum_stock',
        ]));

        $this->syncSuppliers($product, $request->input('supplier_ids'), $request->input('supplier_prices'));

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->stockMovements()->exists()) {
            return redirect()->route('products.index')
                ->with('error', 'Produk tidak dapat dihapus karena memiliki riwayat pergerakan stok.');
        }

        $product->suppliers()->detach();
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    private function syncSuppliers(Product $product, ?array $supplierIds, ?array $supplierPrices): void
    {
        $syncData = [];
        if (! empty($supplierIds)) {
            foreach ($supplierIds as $idx => $supplierId) {
                $price = isset($supplierPrices[$idx]) && is_numeric($supplierPrices[$idx])
                    ? $supplierPrices[$idx]
                    : null;
                $syncData[$supplierId] = ['supply_price' => $price];
            }
        }

        $product->suppliers()->sync($syncData);
    }
}
