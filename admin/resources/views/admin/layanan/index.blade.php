@extends('layouts.admin')

@section('title', 'Katalog Alat Event')

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
        background: rgba(59, 130, 246, 0.01);
        color: var(--text-primary);
    }

    .layanan-info {
        display: flex;
        flex-direction: column;
    }

    .layanan-nama {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .layanan-deskripsi {
        font-size: 0.8rem;
        color: var(--text-secondary);
        max-width: 350px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-top: 0.25rem;
    }

    .kategori-badge {
        background: rgba(59, 130, 246, 0.06);
        color: var(--accent-primary);
        padding: 0.25rem 0.65rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        border: 1px solid rgba(59, 130, 246, 0.12);
        white-space: nowrap;
    }

    .price-badge {
        font-family: monospace;
        color: #10b981;
        font-weight: 700;
        font-size: 1rem;
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
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.25);
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.35);
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Katalog Alat & Paket Event</h1>
        <p>Kelola data katalog barang sewa dan paket logistik event yang tersedia untuk pelanggan.</p>
    </div>
    <div>
        <a href="{{ route('admin.layanan.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Alat / Paket
        </a>
    </div>
</div>

<div class="glass-card">
    <div class="table-container">
        @if($layanans->isEmpty())
            <div style="text-align: center; padding: 3rem 0; color: var(--text-secondary);">
                <i class="fa-solid fa-boxes-stacked" style="font-size: 3rem; margin-bottom: 1rem; color: var(--border-color);"></i>
                <p>Belum ada data katalog alat event.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Alat / Paket</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th>Harga Sewa</th>
                        <th>Tipe</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($layanans as $layanan)
                        <tr>
                            <td>
                                <div class="layanan-info">
                                    <span class="layanan-nama">{{ $layanan->nama_layanan }}</span>
                                    @if($layanan->deskripsi)
                                        <span class="layanan-deskripsi" title="{{ $layanan->deskripsi }}">{{ $layanan->deskripsi }}</span>
                                    @else
                                        <span class="layanan-deskripsi" style="font-style: italic;">Tidak ada deskripsi.</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="kategori-badge">{{ $layanan->kategori }}</span>
                            </td>
                            <td>
                                <strong>{{ $layanan->satuan }}</strong>
                            </td>
                            <td>
                                <span class="price-badge">Rp {{ number_format($layanan->harga, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                @if($layanan->is_paket)
                                    <span class="badge badge-success" style="background: rgba(16, 185, 129, 0.08); border-color: rgba(16, 185, 129, 0.18); color: var(--success);">Paket</span>
                                @else
                                    <span class="badge badge-process" style="background: rgba(59, 130, 246, 0.08); border-color: rgba(59, 130, 246, 0.18); color: var(--accent-primary);">Satuan</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-action-container">
                                    <a href="{{ route('admin.layanan.edit', $layanan->id) }}" class="btn btn-outline btn-sm">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.layanan.destroy', $layanan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alat {{ $layanan->nama_layanan }} dari katalog?');">
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
