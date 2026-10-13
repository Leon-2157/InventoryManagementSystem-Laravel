@extends('layouts.app')

@section('title', 'Detail Produk: ' . $product->name)
@section('breadcrumb')
    <a href="{{ route('products.index') }}" class="text-decoration-none text-secondary">Produk</a> / Detail
@endsection

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold mb-1">{{ $product->name }}</h1>
        <div class="text-muted small">
            SKU: <span class="font-monospace fw-semibold">{{ $product->sku }}</span> &bull;
            Kategori: <span class="badge bg-light text-dark border">{{ $product->category->name ?? '-' }}</span>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        <a href="{{ route('transactions.stock-in') }}" class="btn btn-outline-success btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Stok Masuk
        </a>
        @if ($product->stock > 0)
            <a href="{{ route('transactions.stock-out') }}" class="btn btn-outline-warning btn-sm">
                <i class="bi bi-dash-lg me-1"></i> Stok Keluar
            </a>
        @endif
        @if (auth()->user()->isAdmin())
            <a href="{{ route('products.edit', $product) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-pencil me-1"></i> Ubah Data
            </a>
        @endif
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Informasi Ringkas Produk -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header fw-bold">Informasi Produk</div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted small">Harga Satuan</span>
                        <span class="fw-bold fs-5 text-dark">{{ formatRupiah($product->price) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted small">Stok Saat Ini</span>
                        <div>
                            <span class="fw-bold fs-5">{{ $product->stock }} Unit</span>
                            @if ($product->isLowStock())
                                <span class="badge-status badge-low-stock ms-2">Menipis</span>
                            @else
                                <span class="badge-status badge-in-stock ms-2">Aman</span>
                            @endif
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted small">Batas Minimum Stok</span>
                        <span class="fw-semibold">{{ $product->minimum_stock }} Unit</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <span class="text-muted small">Tanggal Dibuat</span>
                        <span class="small">{{ $product->created_at->format('d/m/Y H:i') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Daftar Supplier Terkait -->
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header fw-bold">Supplier Pemasok</div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Nama Supplier</th>
                            <th>Kontak</th>
                            <th>Alamat</th>
                            <th class="text-end">Harga Pasok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($product->suppliers as $sup)
                            <tr>
                                <td class="fw-semibold">{{ $sup->name }}</td>
                                <td class="small">{{ $sup->contact ?? '-' }}</td>
                                <td class="small text-muted">{{ $sup->address ?? '-' }}</td>
                                <td class="text-end fw-semibold">
                                    {{ $sup->pivot->supplier_price ? formatRupiah($sup->pivot->supplier_price) : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    Belum ada supplier yang ditautkan ke produk ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Riwayat Pergerakan Stok Produk Ini -->
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between">
        <span class="fw-bold">10 Riwayat Pergerakan Stok Terakhir</span>
        <a href="{{ route('transactions.index', ['product_id' => $product->id]) }}" class="btn btn-sm btn-link text-decoration-none">
            Semua Riwayat
        </a>
    </div>
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th class="text-center">Tipe</th>
                    <th class="text-center">Kuantitas</th>
                    <th>Operator</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($product->stockMovements as $movement)
                    <tr>
                        <td class="small text-muted">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-center">
                            @if ($movement->type === 'IN')
                                <span class="badge-status badge-movement-in">IN</span>
                            @else
                                <span class="badge-status badge-movement-out">OUT</span>
                            @endif
                        </td>
                        <td class="text-center fw-semibold">
                            {{ $movement->type === 'IN' ? '+' : '-' }}{{ number_format($movement->quantity) }}
                        </td>
                        <td class="small">
                            {{ $movement->user->profile?->full_name ?? $movement->user->username ?? '-' }}
                        </td>
                        <td class="small text-muted">{{ $movement->note ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            Belum ada riwayat transaksi stok untuk produk ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
