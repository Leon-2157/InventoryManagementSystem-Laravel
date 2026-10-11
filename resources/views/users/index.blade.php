@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('breadcrumb', 'Pengguna')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h1 class="h4 fw-bold mb-1">Manajemen Pengguna</h1>
        <p class="text-muted small mb-0">Kelola akun pengguna, hak akses, dan peran staf sistem</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
        <i class="bi bi-person-plus"></i>
        <span>Tambah Pengguna</span>
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Username</th>
                    <th>Nama Lengkap</th>
                    <th class="text-center" style="width: 140px;">Peran (Role)</th>
                    <th style="width: 180px;">Terdaftar Sejak</th>
                    <th class="text-end" style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $index => $u)
                    <tr class="{{ $u->id === auth()->id() ? 'table-light' : '' }}">
                        <td class="text-muted small">{{ $index + 1 }}</td>
                        <td>
                            <span class="fw-semibold text-dark font-monospace">{{ $u->username }}</span>
                            @if ($u->id === auth()->id())
                                <span class="badge bg-primary-subtle text-primary ms-1 small">Anda</span>
                            @endif
                        </td>
                        <td>{{ $u->profile?->full_name ?? '-' }}</td>
                        <td class="text-center">
                            @if ($u->role === 'admin')
                                <span class="badge bg-primary">Admin</span>
                            @else
                                <span class="badge bg-secondary">Staff</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $u->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('users.edit', $u) }}" class="btn btn-outline-primary" title="Ubah Data">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if ($u->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $u) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini? Pengguna yang memiliki riwayat pergerakan stok tidak dapat dihapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-outline-secondary" disabled title="Tidak dapat menghapus akun sendiri">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
