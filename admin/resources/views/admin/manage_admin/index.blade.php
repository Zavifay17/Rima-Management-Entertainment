@extends('layouts.admin')

@section('title', 'Kelola Admin')

@section('styles')
<style>
    .table-container {
        width: 100%;
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    th {
        background: rgba(0, 0, 0, 0.01);
        color: var(--text-primary);
        font-weight: 600;
        padding: 1.25rem 1.5rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid var(--border-color);
    }

    td {
        padding: 1.25rem 1.5rem;
        font-size: 0.95rem;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }

    tr:hover td {
        background: rgba(0, 0, 0, 0.01);
        color: var(--text-primary);
    }

    .btn-action-container {
        display: flex;
        justify-content: flex-end;
        gap: 0.5rem;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        border-radius: 8px;
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.45);
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Kelola Admin</h1>
        <p>Manajemen data akun administrator</p>
    </div>
    <div>
        <a href="{{ route('admin.manage-admin.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Admin
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success">
    <i class="fa-solid fa-circle-check"></i>
    <div>{{ session('success') }}</div>
</div>
@endif

<div class="glass-card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Username</th>
                    <th>No HP</th>
                    <th>Dibuat Oleh</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $index => $admin)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong style="color: var(--text-primary);">{{ $admin->nama }}</strong></td>
                    <td>@ {{ $admin->username }}</td>
                    <td>{{ $admin->no_hp }}</td>
                    <td>{{ $admin->superadmin ? $admin->superadmin->username : '-' }}</td>
                    <td>
                        <div class="btn-action-container">
                            <a href="{{ route('admin.manage-admin.edit', $admin->id_admin) }}" class="btn btn-outline btn-sm" title="Edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('admin.manage-admin.destroy', $admin->id_admin) }}" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus admin ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center" style="text-align: center; padding: 3rem 0;">Belum ada data admin.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
