<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(): View
    {
        $suppliers = Supplier::withCount('products')->orderBy('name')->get();

        return view('suppliers.index', compact('suppliers'));
    }

    public function create(): View
    {
        return view('suppliers.create');
    }

    public function store(StoreSupplierRequest $request): RedirectResponse
    {
        Supplier::create($request->validated());

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier): View
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(StoreSupplierRequest $request, Supplier $supplier): RedirectResponse
    {
        $supplier->update($request->validated());

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        if ($supplier->products()->exists()) {
            return redirect()->route('suppliers.index')
                ->with('error', 'Supplier tidak dapat dihapus karena masih terkait dengan produk.');
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus.');
    }
}
