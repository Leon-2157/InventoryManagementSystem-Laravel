@extends('layouts.auth')

@section('title', 'Masuk ke Sistem')

@section('content')
<form action="{{ route('login') }}" method="POST" novalidate>
    @csrf

    <div class="mb-3">
        <label for="username" class="form-label fw-semibold small">Username</label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted border-end-0">
                <i class="bi bi-person"></i>
            </span>
            <input type="text"
                   name="username"
                   id="username"
                   class="form-control border-start-0 @error('username') is-invalid @enderror"
                   value="{{ old('username') }}"
                   required
                   autofocus
                   placeholder="Masukkan username"
                   autocomplete="username">
            @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mb-4">
        <label for="password" class="form-label fw-semibold small">Password</label>
        <div class="input-group">
            <span class="input-group-text bg-light text-muted border-end-0">
                <i class="bi bi-key"></i>
            </span>
            <input type="password"
                   name="password"
                   id="password"
                   class="form-control border-start-0 @error('password') is-invalid @enderror"
                   required
                   placeholder="Masukkan password"
                   autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
    </button>
</form>
@endsection
