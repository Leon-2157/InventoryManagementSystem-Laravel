@extends('layouts.app')

@section('title', 'Ubah Pengguna: ' . $user->username)
@section('breadcrumb')
    <a href="{{ route('users.index') }}" class="text-decoration-none text-secondary">Pengguna</a> / Ubah
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="fw-bold">Ubah Data Pengguna: {{ $user->username }}</span>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('users.update', $user) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="username" class="form-label fw-semibold small">Username <span class="text-danger">*</span></label>
                        <input type="text"
                               name="username"
                               id="username"
                               class="form-control font-monospace @error('username') is-invalid @enderror"
                               value="{{ old('username', $user->username) }}"
                               required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="full_name" class="form-label fw-semibold small">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text"
                               name="full_name"
                               id="full_name"
                               class="form-control @error('full_name') is-invalid @enderror"
                               value="{{ old('full_name', $user->profile?->full_name) }}"
                               required>
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label fw-semibold small">Peran (Role) <span class="text-danger">*</span></label>
                        @if ($user->id === auth()->id())
                            <input type="hidden" name="role" value="{{ $user->role }}">
                            <select class="form-select bg-light" disabled>
                                <option selected>{{ ucfirst($user->role) }} (Akun aktif Anda - role tidak dapat diubah)</option>
                            </select>
                            <div class="form-text text-warning">
                                <i class="bi bi-shield-lock"></i> Anda tidak dapat mengubah peran akun Anda sendiri.
                            </div>
                        @else
                            <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>Staff</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        @endif
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold small">Password Baru (Opsional)</label>
                        <input type="password"
                               name="password"
                               id="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Biarkan kosong jika tidak ingin mengubah password"
                               autocomplete="new-password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Perbarui Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
