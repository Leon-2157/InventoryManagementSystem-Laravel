@extends('layouts.app')

@section('title', 'Ubah Supplier: ' . $supplier->name)
@section('breadcrumb')
    <a href="{{ route('suppliers.index') }}" class="text-decoration-none text-secondary">Supplier</a> / Ubah
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="fw-bold">Ubah Supplier: {{ $supplier->name }}</span>
                <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('suppliers.update', $supplier) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold small">Nama Perusahaan / Supplier <span class="text-danger">*</span></label>
                        <input type="text"
                               name="name"
                               id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $supplier->name) }}"
                               required
                               autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="contact" class="form-label fw-semibold small">Kontak / No. Telepon</label>
                        <input type="text"
                               name="contact"
                               id="contact"
                               class="form-control @error('contact') is-invalid @enderror"
                               value="{{ old('contact', $supplier->contact) }}">
                        @error('contact')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="address" class="form-label fw-semibold small">Alamat Lengkap</label>
                        <textarea name="address"
                                  id="address"
                                  rows="3"
                                  class="form-control @error('address') is-invalid @enderror">{{ old('address', $supplier->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Perbarui Supplier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
