@extends('layouts.app')

@section('title', 'Kategori Produk')
@section('breadcrumb', 'Kategori')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold mb-1">Kategori Produk</h1>
        <p class="text-muted small mb-0">Kelola kelompok dan klasifikasi barang inventaris</p>
    </div>
    @if (auth()->user()->isAdmin())
        <a href="{{ route('categories.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Kategori</span>
        </a>
    @endif
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Nama Kategori</th>
                    <th class="text-center" style="width: 160px;">Jumlah Produk</th>
                    <th class="text-end" style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $index => $cat)
                    <tr>
                        <td class="text-muted small">{{ $index + 1 }}</td>
                        <td class="fw-semibold text-dark">
                            <a href="{{ route('products.index', ['category_id' => $cat->id]) }}" class="text-decoration-none text-dark">
                                {{ $cat->name }}
                            </a>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border">{{ $cat->products_count }} Produk</span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                @if (auth()->user()->isAdmin())
                                    <a href="{{ route('categories.edit', $cat) }}" class="btn btn-outline-primary" title="Ubah">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('categories.destroy', $cat) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Kategori yang memiliki produk tidak dapat dihapus.')">
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
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-tags fs-1 d-block mb-2 text-secondary"></i>
                            <div class="fw-semibold">Belum ada kategori terdaftar</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
