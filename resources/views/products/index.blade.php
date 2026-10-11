@extends('layouts.app')

@section('title', 'Daftar Produk')
@section('breadcrumb', 'Manajemen Produk')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold mb-1">Daftar Produk</h1>
        <p class="text-muted small mb-0">Kelola katalog barang, stok, dan harga inventaris</p>
    </div>
    @if (auth()->user()->isAdmin())
        <a href="{{ route('products.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Produk</span>
        </a>
    @endif
</div>

<!-- Filter dan Pencarian -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('products.index') }}" class="row g-2 align-items-center">
            <input type="hidden" name="sort" value="{{ $sort }}">
            <input type="hidden" name="dir" value="{{ $dir }}">

            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text"
                           name="search"
                           class="form-control border-start-0"
                           placeholder="Cari berdasarkan nama produk atau SKU..."
                           value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-4">
                <select name="category_id" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (string) request('category_id') === (string) $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                @if (request()->hasAny(['search', 'category_id']))
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary" title="Reset filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Tabel Produk -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th>
                        <x-sort-link column="sku" label="SKU" :current-sort="$sort" :current-dir="$dir" />
                    </th>
                    <th>
                        <x-sort-link column="name" label="Nama Produk" :current-sort="$sort" :current-dir="$dir" />
                    </th>
                    <th>Kategori</th>
                    <th class="text-end">
                        <x-sort-link column="price" label="Harga Satuan" :current-sort="$sort" :current-dir="$dir" />
                    </th>
                    <th class="text-center">
                        <x-sort-link column="stock" label="Stok" :current-sort="$sort" :current-dir="$dir" />
                    </th>
                    <th class="text-center">Min. Stok</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td class="fw-semibold text-secondary small font-monospace">{{ $product->sku }}</td>
                        <td>
                            <a href="{{ route('products.show', $product) }}" class="fw-semibold text-decoration-none text-dark">
                                {{ $product->name }}
                            </a>
                            @if ($product->suppliers->isNotEmpty())
                                <div class="small text-muted">
                                    {{ $product->suppliers->pluck('name')->join(', ') }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $product->category->name ?? '-' }}</span>
                        </td>
                        <td class="text-end fw-semibold text-dark">
                            {{ formatRupiah($product->price) }}
                        </td>
                        <td class="text-center">
                            @if ($product->isLowStock())
                                <span class="badge-status badge-low-stock" title="Stok di bawah atau sama dengan batas minimum">
                                    <i class="bi bi-exclamation-triangle"></i> {{ $product->stock }}
                                </span>
                            @else
                                <span class="badge-status badge-in-stock">
                                    {{ $product->stock }}
                                </span>
                            @endif
                        </td>
                        <td class="text-center text-muted small">{{ $product->minimum_stock }}</td>
                        <td class="text-center">
                            @if ($product->stock === 0)
                                <span class="badge bg-danger">Habis</span>
                            @elseif ($product->isLowStock())
                                <span class="badge bg-warning text-dark">Menipis</span>
                            @else
                                <span class="badge bg-success">Tersedia</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-outline-secondary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if (auth()->user()->isAdmin())
                                    <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-primary" title="Ubah">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini? Produk yang memiliki riwayat pergerakan stok tidak dapat dihapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                            <div class="fw-semibold">Tidak ada produk ditemukan</div>
                            <small>Coba sesuaikan kata kunci pencarian atau filter kategori Anda.</small>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($products->hasPages())
        <div class="card-footer bg-white border-top-0 py-3">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
