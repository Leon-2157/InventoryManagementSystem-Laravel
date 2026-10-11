<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('profile')->orderBy('username')->get();

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = User::create([
            'username' => $request->username,
            'password_hash' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        $user->profile()->create([
            'full_name' => $request->full_name,
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        $user->load('profile');

        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        // Business rule: User tidak bisa mengubah role akunnya sendiri jika sedang login
        if ($user->id === Auth::id() && $request->role !== 'admin') {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat mengubah role akun Anda sendiri.');
        }

        $data = [
            'username' => $request->username,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password_hash'] = Hash::make($request->password);
        }

        $user->update($data);

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            ['full_name' => $request->full_name]
        );

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        // Business rule 1: User tidak bisa menghapus akunnya sendiri
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Business rule 2: User tidak bisa dihapus jika ada riwayat transaksi stock
        if ($user->stockMovements()->exists()) {
            return redirect()->route('users.index')
                ->with('error', 'Pengguna tidak dapat dihapus karena memiliki riwayat pergerakan stok.');
        }

        $user->profile()->delete();
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
