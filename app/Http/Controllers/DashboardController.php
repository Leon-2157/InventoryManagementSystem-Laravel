<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalProducts = Product::count();
        $inventoryValue = Product::selectRaw('COALESCE(SUM(stock * price), 0) as total')->value('total');
        $lowStockCount = Product::whereColumn('stock', '<=', 'minimum_stock')->count();
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();

        $stockIn = StockMovement::where('type', 'IN')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('quantity');

        $stockOut = StockMovement::where('type', 'OUT')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('quantity');

        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderByRaw('(stock - minimum_stock) ASC')
            ->limit(10)
            ->get();

        $recentTransactions = StockMovement::with(['product', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalProducts', 'inventoryValue', 'lowStockCount',
            'totalCategories', 'totalSuppliers',
            'stockIn', 'stockOut',
            'lowStockProducts', 'recentTransactions'
        ));
    }
}
