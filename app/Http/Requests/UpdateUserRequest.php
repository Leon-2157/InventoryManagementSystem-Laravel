<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        $user = $this->route('user');
        $userId = is_object($user) ? $user->id : $user;

        return [
            'username' => "required|string|max:50|unique:users,username,{$userId}",
            'password' => 'nullable|string|min:6',
            'full_name' => 'required|string|max:100',
            'role' => 'required|in:admin,staff',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'password.min' => 'Password minimal 6 karakter.',
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role tidak valid.',
        ];
    }
}
