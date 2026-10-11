<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'contact' => 'nullable|string|max:100',
            'address' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama supplier wajib diisi.',
        ];
    }
}
