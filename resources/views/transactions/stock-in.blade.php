@extends('layouts.app')

@section('title', 'Catat Stok Masuk (Stock In)')
@section('breadcrumb')
    <a href="{{ route('transactions.index') }}" class="text-decoration-none text-secondary">Transaksi</a> / Stok Masuk
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between bg-white py-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="p-2 bg-success-subtle text-success rounded">
                        <i class="bi bi-box-arrow-in-down fs-5"></i>
                    </span>
                    <div>
                        <div class="fw-bold">Pencatatan Stok Masuk (IN)</div>
                        <small class="text-muted">Menambah persediaan unit barang ke dalam gudang</small>
                    </div>
                </div>
                <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ url('transactions/stock-in') }}" method="POST" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="product_id" class="form-label fw-semibold small">Pilih Produk <span class="text-danger">*</span></label>
                        <select name="product_id" id="product_id" class="form-select @error('product_id') is-invalid @enderror" required autofocus>
                            <option value="">-- Pilih Produk yang Masuk --</option>
                            @foreach ($products as $p)
                                <option value="{{ $p->id }}" {{ (string) old('product_id') === (string) $p->id ? 'selected' : '' }}>
                                    {{ $p->name }} (SKU: {{ $p->sku }} | Stok Saat Ini: {{ $p->stock }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label fw-semibold small">Jumlah Kuantitas Masuk <span class="text-danger">*</span></label>
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
                        <div class="form-text">Masukkan angka bulat positif (minimal 1 unit).</div>
                    </div>

                    <div class="mb-4">
                        <label for="note" class="form-label fw-semibold small">Catatan / Keterangan (Opsional)</label>
                        <textarea name="note"
                                  id="note"
                                  rows="3"
                                  class="form-control @error('note') is-invalid @enderror"
                                  placeholder="Contoh: Penerimaan pesanan PO-1029 dari PT Sumber Makmur">{{ old('note') }}</textarea>
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-check-lg me-1"></i> Catat Stok Masuk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
