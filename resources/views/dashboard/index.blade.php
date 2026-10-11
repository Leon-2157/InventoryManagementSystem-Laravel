@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard Inventaris')

@section('content')
<div class="row g-3 mb-4">
    <!-- Stat: Total Produk -->
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Total Produk</span>
                <span class="p-2 bg-primary-subtle text-primary rounded">
                    <i class="bi bi-box-seam fs-5"></i>
                </span>
            </div>
            <div class="fs-3 fw-bold text-dark">{{ number_format($totalProducts) }}</div>
            <div class="small text-muted mt-1">Item aktif terdaftar</div>
        </div>
    </div>

    <!-- Stat: Nilai Inventaris -->
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Nilai Inventaris</span>
                <span class="p-2 bg-success-subtle text-success rounded">
                    <i class="bi bi-cash-stack fs-5"></i>
                </span>
            </div>
            <div class="fs-4 fw-bold text-dark">{{ formatRupiah($inventoryValue) }}</div>
            <div class="small text-muted mt-1">Estimasi total nilai aset</div>
        </div>
    </div>

    <!-- Stat: Low Stock -->
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Stok Menipis</span>
                <span class="p-2 bg-warning-subtle text-warning rounded">
                    <i class="bi bi-exclamation-triangle fs-5"></i>
                </span>
            </div>
            <div class="fs-3 fw-bold {{ $lowStockCount > 0 ? 'text-danger' : 'text-dark' }}">
                {{ number_format($lowStockCount) }}
            </div>
            <div class="small text-muted mt-1">
                @if ($lowStockCount > 0)
                    <span class="text-danger fw-semibold">Perlu restock segera</span>
                @else
                    Semua stok aman
                @endif
            </div>
        </div>
    </div>

    <!-- Stat: Transaksi Bulan Ini -->
    <div class="col-sm-6 col-xl-3">
        <div class="card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small fw-medium">Aktivitas Bulan Ini</span>
                <span class="p-2 bg-info-subtle text-info rounded">
                    <i class="bi bi-arrow-left-right fs-5"></i>
                </span>
            </div>
            <div class="d-flex align-items-baseline gap-2">
                <span class="text-success fw-bold fs-5">+{{ number_format($stockIn) }}</span>
                <span class="text-muted">/</span>
                <span class="text-danger fw-bold fs-5">-{{ number_format($stockOut) }}</span>
            </div>
            <div class="small text-muted mt-1">Unit Masuk vs Unit Keluar</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Tabel Low Stock Alert -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="fw-bold">
                    <i class="bi bi-exclamation-circle text-warning me-2"></i>Produk Stok Menipis
                </span>
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-link text-decoration-none">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>SKU / Nama</th>
                            <th>Kategori</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center">Min.</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lowStockProducts as $product)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $product->name }}</div>
                                    <small class="text-muted">{{ $product->sku }}</small>
                                </td>
                                <td>{{ $product->category->name ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge-status badge-low-stock">{{ $product->stock }}</span>
                                </td>
                                <td class="text-center text-muted">{{ $product->minimum_stock }}</td>
                                <td class="text-end">
                                    <a href="{{ route('transactions.stock-in') }}" class="btn btn-outline-primary btn-sm py-1 px-2" title="Tambah Stok">
                                        <i class="bi bi-plus-lg"></i> In
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="bi bi-check-circle text-success fs-3 d-block mb-1"></i>
                                    Tidak ada produk dengan stok di bawah batas minimum.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Riwayat Transaksi Terbaru -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="fw-bold">
                    <i class="bi bi-clock-history text-primary me-2"></i>Aktivitas Transaksi Terbaru
                </span>
                <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-link text-decoration-none">Riwayat Lengkap</a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Produk</th>
                            <th class="text-center">Tipe</th>
                            <th class="text-center">Qty</th>
                            <th>Operator</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentTransactions as $movement)
                            <tr>
                                <td class="small text-muted">{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="fw-medium">{{ $movement->product->name ?? 'Produk Dihapus' }}</span>
                                </td>
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
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox text-muted fs-3 d-block mb-1"></i>
                                    Belum ada catatan aktivitas transaksi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
