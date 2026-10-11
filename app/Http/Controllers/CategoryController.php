<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('products')->orderBy('name')->get();

        return view('categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('categories.create');
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        Category::create($request->validated());

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category): View
    {
        return view('categories.edit', compact('category'));
    }

    public function update(StoreCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return redirect()->route('categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh produk.');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
