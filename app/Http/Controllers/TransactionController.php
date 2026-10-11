<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockInRequest;
use App\Http\Requests\StockOutRequest;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $query = StockMovement::with(['product', 'user.profile'])->latest();

        if ($request->filled('type')) {
            $query->where('type', $request->query('type'));
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->query('product_id'));
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->query('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->query('end_date'));
        }

        $transactions = $query->paginate(15)->withQueryString();
        $products = Product::orderBy('name')->get();

        return view('transactions.index', compact('transactions', 'products'));
    }

    public function stockInForm(): View
    {
        $products = Product::orderBy('name')->get();

        return view('transactions.stock-in', compact('products'));
    }

    public function stockIn(StockInRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $product = Product::where('id', $request->product_id)->lockForUpdate()->firstOrFail();
            $product->increment('stock', $request->quantity);

            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => 'IN',
                'quantity' => $request->quantity,
                'note' => $request->note,
            ]);
        });

        return redirect()->route('transactions.index')->with('success', 'Stok masuk berhasil dicatat.');
    }

    public function stockOutForm(): View
    {
        $products = Product::where('stock', '>', 0)->orderBy('name')->get();

        return view('transactions.stock-out', compact('products'));
    }

    public function stockOut(StockOutRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request) {
                // lockForUpdate to prevent race condition - mandatory business rule
                $product = Product::where('id', $request->product_id)->lockForUpdate()->firstOrFail();

                if ($request->quantity > $product->stock) {
                    throw new \RuntimeException("Stok tidak mencukupi. Sisa stok tersedia: {$product->stock}.");
                }

                $product->decrement('stock', $request->quantity);

                StockMovement::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'OUT',
                    'quantity' => $request->quantity,
                    'note' => $request->note,
                ]);
            });

            return redirect()->route('transactions.index')->with('success', 'Stok keluar berhasil dicatat.');
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
