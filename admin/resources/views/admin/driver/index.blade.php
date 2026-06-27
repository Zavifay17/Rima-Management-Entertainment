@extends('layouts.admin')

@section('title', 'Manajemen Driver')

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

    .driver-info {
        display: flex;
        flex-direction: column;
    }

    .driver-nama {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .driver-username {
        font-size: 0.8rem;
        color: var(--text-secondary);
    }

    .phone-number {
        font-family: 'Courier New', Courier, monospace;
        color: #a855f7;
        font-weight: 600;
        background: rgba(168, 85, 247, 0.08);
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        border: 1px solid rgba(168, 85, 247, 0.15);
    }

    .task-count {
        background: rgba(99, 102, 241, 0.1);
        color: var(--accent-primary);
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        font-weight: 700;
        display: inline-block;
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

    /* Status Toggle Form */
    .status-toggle-btn {
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 0;
        display: inline-flex;
        align-items: center;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Manajemen Driver Logistik</h1>
        <p>Kelola data driver yang bertugas mengantar dan menjemput alat/barang sewa.</p>
    </div>
    <div>
        <a href="{{ route('admin.driver.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Driver Baru
        </a>
    </div>
</div>

<div class="glass-card">
    <div class="table-container">
        @if($drivers->isEmpty())
            <div style="text-align: center; padding: 3rem 0; color: var(--text-secondary);">
                <i class="fa-solid fa-id-card-clip" style="font-size: 3rem; margin-bottom: 1rem; color: var(--border-color);"></i>
                <p>Belum ada data driver logistik.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Driver</th>
                        <th>No. Handphone</th>
                        <th>Jumlah Tugas</th>
                        <th>Status Aktif</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($drivers as $driver)
                        <tr>
                            <td>
                                <div class="driver-info">
                                    <span class="driver-nama">{{ $driver->nama }}</span>
                                    <span class="driver-username">@ {{ $driver->username }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="phone-number">{{ $driver->no_hp }}</span>
                            </td>
                            <td>
                                <span class="task-count">{{ $driver->pengirimans_count }} Tugas</span>
                            </td>
                            <td>
                                <form action="{{ route('admin.driver.toggle-status', $driver->id_driver) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="status-toggle-btn" title="Klik untuk mengubah status">
                                        @if($driver->status_aktif)
                                            <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> Aktif</span>
                                        @else
                                            <span class="badge badge-pending" style="background: rgba(156, 163, 175, 0.15); color: #9ca3af; border-color: rgba(156, 163, 175, 0.3);"><i class="fa-solid fa-circle-minus"></i> Nonaktif</span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div class="btn-action-container">
                                    <a href="{{ route('admin.driver.edit', $driver->id_driver) }}" class="btn btn-outline btn-sm">
                                        <i class="fa-solid fa-user-pen"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.driver.destroy', $driver->id_driver) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus driver {{ $driver->nama }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fa-solid fa-trash-can"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
