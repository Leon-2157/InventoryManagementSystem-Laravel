@extends('layouts.app')

@section('title', 'Daftar Supplier')
@section('breadcrumb', 'Supplier')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold mb-1">Daftar Supplier</h1>
        <p class="text-muted small mb-0">Kelola data mitra penyedia dan pemasok barang inventaris</p>
    </div>
    @if (auth()->user()->isAdmin())
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Supplier</span>
        </a>
    @endif
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Nama Supplier</th>
                    <th>Kontak / Telepon</th>
                    <th>Alamat</th>
                    <th class="text-center" style="width: 160px;">Produk Dipasok</th>
                    <th class="text-end" style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($suppliers as $index => $sup)
                    <tr>
                        <td class="text-muted small">{{ $index + 1 }}</td>
                        <td class="fw-semibold text-dark">{{ $sup->name }}</td>
                        <td class="small">{{ $sup->contact ?? '-' }}</td>
                        <td class="small text-muted">{{ $sup->address ?? '-' }}</td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border">{{ $sup->products_count }} Produk</span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                @if (auth()->user()->isAdmin())
                                    <a href="{{ route('suppliers.edit', $sup) }}" class="btn btn-outline-primary" title="Ubah">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('suppliers.destroy', $sup) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus supplier ini? Supplier yang terikat dengan produk tidak dapat dihapus.')">
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
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-truck fs-1 d-block mb-2 text-secondary"></i>
                            <div class="fw-semibold">Belum ada supplier terdaftar</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
