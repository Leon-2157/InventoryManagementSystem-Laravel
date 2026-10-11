@extends('layouts.app')

@section('title', 'Ubah Produk: ' . $product->name)
@section('breadcrumb')
    <a href="{{ route('products.index') }}" class="text-decoration-none text-secondary">Produk</a> / Ubah
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="fw-bold">Ubah Data Produk: {{ $product->name }}</span>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('products.update', $product) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="sku" class="form-label fw-semibold small">SKU (Kode Produk) <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="sku"
                                   id="sku"
                                   class="form-control font-monospace @error('sku') is-invalid @enderror"
                                   value="{{ old('sku', $product->sku) }}"
                                   required>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="category_id" class="form-label fw-semibold small">Kategori <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
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
                               value="{{ old('name', $product->name) }}"
                               required>
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
                                       value="{{ old('price', (int)$product->price) }}"
                                       required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Stok Saat Ini (Read-only)</label>
                            <input type="text"
                                   class="form-control bg-light"
                                   value="{{ $product->stock }} Unit"
                                   disabled>
                            <div class="form-text">Perubahan stok dicatat melalui Transaksi.</div>
                        </div>

                        <div class="col-md-4">
                            <label for="minimum_stock" class="form-label fw-semibold small">Batas Minimum Stok <span class="text-danger">*</span></label>
                            <input type="number"
                                   name="minimum_stock"
                                   id="minimum_stock"
                                   min="0"
                                   class="form-control @error('minimum_stock') is-invalid @enderror"
                                   value="{{ old('minimum_stock', $product->minimum_stock) }}"
                                   required>
                            @error('minimum_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @if ($suppliers->isNotEmpty())
                        @php
                            $attachedSuppliers = $product->suppliers->keyBy('id');
                        @endphp
                        <div class="card bg-light border-0 mb-4 p-3">
                            <label class="form-label fw-bold small text-dark mb-2">Relasi Supplier & Harga Pasok</label>
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless align-middle mb-0">
                                    <thead>
                                        <tr class="text-muted small">
                                            <th style="width: 40px;">Pilih</th>
                                            <th>Nama Supplier</th>
                                            <th style="width: 200px;">Harga Pasok (Rp)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($suppliers as $idx => $sup)
                                            @php
                                                $isAttached = $attachedSuppliers->has($sup->id);
                                                $existingPrice = $isAttached ? $attachedSuppliers->get($sup->id)->pivot->supply_price : null;
                                                $isChecked = old('supplier_ids') !== null
                                                    ? (is_array(old('supplier_ids')) && in_array($sup->id, old('supplier_ids')))
                                                    : $isAttached;
                                                $valPrice = old("supplier_prices.$idx", $existingPrice !== null ? (int)$existingPrice : '');
                                            @endphp
                                            <tr>
                                                <td>
                                                    <input type="checkbox"
                                                           name="supplier_ids[]"
                                                           value="{{ $sup->id }}"
                                                           class="form-check-input"
                                                           id="sup_{{ $sup->id }}"
                                                           {{ $isChecked ? 'checked' : '' }}>
                                                </td>
                                                <td>
                                                    <label for="sup_{{ $sup->id }}" class="form-check-label user-select-none mb-0">
                                                        <div class="fw-semibold">{{ $sup->name }}</div>
                                                        <small class="text-muted">{{ $sup->contact }}</small>
                                                    </label>
                                                </td>
                                                <td>
                                                    <input type="number"
                                                           name="supplier_prices[]"
                                                           step="1"
                                                           min="0"
                                                           placeholder="Harga pasok"
                                                           class="form-control form-control-sm"
                                                           value="{{ $valPrice }}">
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Perbarui Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
