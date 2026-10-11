@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')
@section('breadcrumb')
    <a href="{{ route('users.index') }}" class="text-decoration-none text-secondary">Pengguna</a> / Tambah
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="fw-bold">Formulir Pengguna Baru</span>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('users.store') }}" method="POST" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="username" class="form-label fw-semibold small">Username <span class="text-danger">*</span></label>
                        <input type="text"
                               name="username"
                               id="username"
                               class="form-control font-monospace @error('username') is-invalid @enderror"
                               value="{{ old('username') }}"
                               required
                               autofocus
                               placeholder="Contoh: staff_gudang">
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
                               value="{{ old('full_name') }}"
                               required
                               placeholder="Contoh: Budi Santoso">
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label fw-semibold small">Peran (Role) <span class="text-danger">*</span></label>
                        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff (Pencatatan Stok)</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator (Akses Penuh)</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold small">Password <span class="text-danger">*</span></label>
                        <input type="password"
                               name="password"
                               id="password"
                               class="form-control @error('password') is-invalid @enderror"
                               required
                               placeholder="Minimal 6 karakter"
                               autocomplete="new-password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Simpan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
