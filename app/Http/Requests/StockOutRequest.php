<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockOutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Produk wajib dipilih.',
            'product_id.exists' => 'Produk tidak ditemukan.',
            'quantity.required' => 'Kuantitas wajib diisi.',
            'quantity.min' => 'Kuantitas harus minimal 1.',
        ];
    }
}
