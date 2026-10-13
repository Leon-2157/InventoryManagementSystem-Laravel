@extends('layouts.app')

@section('title', 'Tambah Produk Baru')
@section('breadcrumb')
    <a href="{{ route('products.index') }}" class="text-decoration-none text-secondary">Produk</a> / Tambah
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="fw-bold">Formulir Produk Baru</span>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('products.store') }}" method="POST" novalidate>
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="sku" class="form-label fw-semibold small">SKU (Kode Produk) <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="sku"
                                   id="sku"
                                   class="form-control font-monospace @error('sku') is-invalid @enderror"
                                   value="{{ old('sku') }}"
                                   required
                                   placeholder="Contoh: PRD-001">
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Harus unik untuk setiap produk.</div>
                        </div>

                        <div class="col-md-6">
                            <label for="category_id" class="form-label fw-semibold small">Kategori <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold small">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text"
                               name="name"
                               id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               required
                               placeholder="Contoh: Kertas HVS A4 80gr">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="price" class="form-label fw-semibold small">Harga Jual (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">Rp</span>
                                <input type="number"
                                       name="price"
                                       id="price"
                                       step="1"
                                       min="0"
                                       class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price', 0) }}"
                                       required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="stock" class="form-label fw-semibold small">Stok Awal <span class="text-danger">*</span></label>
                            <input type="number"
                                   name="stock"
                                   id="stock"
                                   min="0"
                                   class="form-control @error('stock') is-invalid @enderror"
                                   value="{{ old('stock', 0) }}"
                                   required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="minimum_stock" class="form-label fw-semibold small">Batas Minimum Stok <span class="text-danger">*</span></label>
                            <input type="number"
                                   name="minimum_stock"
                                   id="minimum_stock"
                                   min="0"
                                   class="form-control @error('minimum_stock') is-invalid @enderror"
                                   value="{{ old('minimum_stock', 5) }}"
                                   required>
                            @error('minimum_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @if ($suppliers->isNotEmpty())
                        <div class="card bg-light border-0 mb-4 p-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="supplier_id" class="form-label fw-semibold small text-dark">Supplier <span class="text-muted fw-normal">(Opsional)</span></label>
                                    <select name="supplier_id" id="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror">
                                        <option value="">-- Tanpa Supplier / Pilih Nanti --</option>
                                        @foreach ($suppliers as $sup)
                                            <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>
                                                {{ $sup->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="supplier_price" class="form-label fw-semibold small text-dark">Harga Pasok (Rp) <span class="text-muted fw-normal">(Opsional)</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">Rp</span>
                                        <input type="number"
                                               name="supplier_price"
                                               id="supplier_price"
                                               step="1"
                                               min="0"
                                               class="form-control @error('supplier_price') is-invalid @enderror"
                                               placeholder="Harga beli dari supplier"
                                               value="{{ old('supplier_price') }}">
                                        @error('supplier_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
