@extends('layouts.app')

@section('title', 'Catat Stok Keluar (Stock Out)')
@section('breadcrumb')
    <a href="{{ route('transactions.index') }}" class="text-decoration-none text-secondary">Transaksi</a> / Stok Keluar
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between bg-white py-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="p-2 bg-warning-subtle text-warning rounded">
                        <i class="bi bi-box-arrow-up fs-5 text-dark"></i>
                    </span>
                    <div>
                        <div class="fw-bold">Pencatatan Stok Keluar (OUT)</div>
                        <small class="text-muted">Mengurangi persediaan barang untuk penjualan atau pemakaian</small>
                    </div>
                </div>
                <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ url('transactions/stock-out') }}" method="POST" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="product_id" class="form-label fw-semibold small">Pilih Produk <span class="text-danger">*</span></label>
                        <select name="product_id" id="product_id" class="form-select @error('product_id') is-invalid @enderror" required autofocus>
                            <option value="">-- Pilih Produk yang Keluar --</option>
                            @foreach ($products as $p)
                                <option value="{{ $p->id }}" {{ (string) old('product_id') === (string) $p->id ? 'selected' : '' }}>
                                    {{ $p->name }} (SKU: {{ $p->sku }} | Tersedia: {{ $p->stock }} unit)
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label fw-semibold small">Jumlah Kuantitas Keluar <span class="text-danger">*</span></label>
                        <input type="number"
                               name="quantity"
                               id="quantity"
                               min="1"
                               class="form-control @error('quantity') is-invalid @enderror"
                               value="{{ old('quantity', 1) }}"
                               required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Kuantitas keluar tidak boleh melebihi jumlah stok yang tersedia.</div>
                    </div>

                    <div class="mb-4">
                        <label for="note" class="form-label fw-semibold small">Catatan / Keterangan (Opsional)</label>
                        <textarea name="note"
                                  id="note"
                                  rows="3"
                                  class="form-control @error('note') is-invalid @enderror"
                                  placeholder="Contoh: Pengiriman invoice INV-2026-081 ke pelanggan">{{ old('note') }}</textarea>
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-warning px-4 text-dark fw-semibold">
                            <i class="bi bi-check-lg me-1"></i> Catat Stok Keluar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
