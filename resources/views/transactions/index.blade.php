@extends('layouts.app')

@section('title', 'Riwayat Transaksi Stok')
@section('breadcrumb', 'Riwayat Transaksi')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold mb-1">Riwayat Transaksi Stok</h1>
        <p class="text-muted small mb-0">Catatan log mutasi pergerakan barang masuk dan keluar</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('transactions.stock-in') }}" class="btn btn-success d-inline-flex align-items-center gap-2">
            <i class="bi bi-box-arrow-in-down"></i>
            <span>Stok Masuk</span>
        </a>
        <a href="{{ route('transactions.stock-out') }}" class="btn btn-warning d-inline-flex align-items-center gap-2 text-dark">
            <i class="bi bi-box-arrow-up"></i>
            <span>Stok Keluar</span>
        </a>
    </div>
</div>

<!-- Filter Transaksi -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('transactions.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted mb-1">Produk</label>
                <select name="product_id" class="form-select form-select-sm">
                    <option value="">Semua Produk</option>
                    @foreach ($products as $p)
                        <option value="{{ $p->id }}" {{ (string) request('product_id') === (string) $p->id ? 'selected' : '' }}>
                            {{ $p->name }} ({{ $p->sku }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-semibold text-muted mb-1">Jenis Transaksi</label>
                <select name="type" class="form-select form-select-sm">
                    <option value="">Semua Jenis</option>
                    <option value="IN" {{ request('type') === 'IN' ? 'selected' : '' }}>Masuk (IN)</option>
                    <option value="OUT" {{ request('type') === 'OUT' ? 'selected' : '' }}>Keluar (OUT)</option>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-semibold text-muted mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-semibold text-muted mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                    <i class="bi bi-funnel me-1"></i> Terapkan
                </button>
                @if (request()->hasAny(['product_id', 'type', 'start_date', 'end_date']))
                    <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Tabel Transaksi -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th style="width: 160px;">Waktu & Tanggal</th>
                    <th>Produk</th>
                    <th class="text-center" style="width: 100px;">Jenis</th>
                    <th class="text-center" style="width: 120px;">Kuantitas</th>
                    <th>Dicatat Oleh</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transactions as $t)
                    <tr>
                        <td class="small text-muted">{{ $t->created_at->format('d/m/Y H:i:s') }}</td>
                        <td>
                            @if ($t->product)
                                <a href="{{ route('products.show', $t->product) }}" class="fw-semibold text-decoration-none text-dark">
                                    {{ $t->product->name }}
                                </a>
                                <div class="small text-muted font-monospace">{{ $t->product->sku }}</div>
                            @else
                                <span class="text-muted fst-italic">Produk Telah Dihapus</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($t->type === 'IN')
                                <span class="badge-status badge-movement-in">
                                    <i class="bi bi-arrow-down-left"></i> IN
                                </span>
                            @else
                                <span class="badge-status badge-movement-out">
                                    <i class="bi bi-arrow-up-right"></i> OUT
                                </span>
                            @endif
                        </td>
                        <td class="text-center fw-bold {{ $t->type === 'IN' ? 'text-success' : 'text-danger' }}">
                            {{ $t->type === 'IN' ? '+' : '-' }}{{ number_format($t->quantity) }}
                        </td>
                        <td class="small">
                            {{ $t->user->profile?->full_name ?? $t->user->username ?? '-' }}
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $t->user->role ?? '' }}</div>
                        </td>
                        <td class="small text-muted">{{ $t->note ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-clock-history fs-1 d-block mb-2 text-secondary"></i>
                            <div class="fw-semibold">Belum ada riwayat transaksi yang cocok</div>
                            <small>Data mutasi stok akan muncul secara otomatis setelah transaksi dicatat.</small>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($transactions->hasPages())
        <div class="card-footer bg-white border-top-0 py-3">
            {{ $transactions->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
