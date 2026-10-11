<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6',
            'full_name' => 'required|string|max:100',
            'role' => 'required|in:admin,staff',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role tidak valid.',
        ];
    }
}
