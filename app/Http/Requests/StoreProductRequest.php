<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|integer|exists:categories,id',
            'sku' => 'required|string|max:50|unique:products,sku',
            'name' => 'required|string|max:150',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
            'supplier_ids' => 'nullable|array',
            'supplier_ids.*' => 'integer|exists:suppliers,id',
            'supplier_prices' => 'nullable|array',
            'supplier_prices.*' => 'numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori tidak valid.',
            'sku.required' => 'SKU wajib diisi.',
            'sku.unique' => 'SKU sudah digunakan oleh produk lain.',
            'name.required' => 'Nama produk wajib diisi.',
            'price.min' => 'Harga tidak boleh negatif.',
            'stock.min' => 'Stok tidak boleh negatif.',
            'minimum_stock.min' => 'Minimum stok tidak boleh negatif.',
        ];
    }
}
