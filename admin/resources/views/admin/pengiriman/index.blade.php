@extends('layouts.admin')

@section('title', 'Daftar Pengiriman Driver')

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
        background: rgba(255, 255, 255, 0.02);
        color: var(--text-primary);
        font-weight: 600;
        padding: 1.25rem 1.5rem;
        font-size: 0.9rem;
        text-transform: uppercase;
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

    .order-link {
        color: var(--accent-primary);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s;
    }

    .order-link:hover {
        color: var(--accent-secondary);
    }

    .driver-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-primary);
        font-weight: 600;
        white-space: nowrap;
    }

    .driver-badge i {
        color: var(--accent-secondary);
    }

    .date-badge {
        font-family: 'Courier New', Courier, monospace;
        background: rgba(0, 0, 0, 0.02);
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        border: 1px solid var(--border-color);
        font-weight: 500;
        color: var(--text-primary);
        white-space: nowrap;
    }

    .tipe-badge {
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 8px;
        font-size: 0.85rem;
    }

    .tipe-badge-antar {
        background: rgba(99, 102, 241, 0.1);
        color: var(--accent-primary);
        border: 1px solid rgba(99, 102, 241, 0.2);
    }

    .tipe-badge-jemput {
        background: rgba(168, 85, 247, 0.1);
        color: var(--accent-secondary);
        border: 1px solid rgba(168, 85, 247, 0.2);
    }

    .catatan {
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 0.85rem;
    }

    /* Granular Driver Status Badges */
    .badge-accepted {
        background: rgba(99, 102, 241, 0.08);
        color: #6366f1;
        border-color: rgba(99, 102, 241, 0.18);
    }
    .badge-pickup {
        background: rgba(6, 182, 212, 0.08);
        color: #06b6d4;
        border-color: rgba(6, 182, 212, 0.18);
    }
    .badge-ontheway {
        background: rgba(59, 130, 246, 0.08);
        color: #3b82f6;
        border-color: rgba(59, 130, 246, 0.18);
    }
    .badge-arrived {
        background: rgba(236, 72, 153, 0.08);
        color: #ec4899;
        border-color: rgba(236, 72, 153, 0.18);
    }
    .badge-cancelled {
        background: rgba(239, 68, 68, 0.08);
        color: var(--danger);
        border-color: rgba(239, 68, 68, 0.18);
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Pengiriman Driver</h1>
        <p>Kelola dan pantau alokasi tugas pengantaran atau penjemputan barang sewa oleh Driver.</p>
    </div>
    <div>
        <a href="{{ route('admin.pengiriman.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Alokasikan Driver
        </a>
    </div>
</div>

<div class="glass-card">
    <div class="table-container">
        @if($pengirimans->isEmpty())
            <div style="text-align: center; padding: 3rem 0; color: var(--text-secondary);">
                <i class="fa-solid fa-truck-fast" style="font-size: 3rem; margin-bottom: 1rem; color: var(--border-color);"></i>
                <p>Belum ada jadwal pengiriman yang dialokasikan ke Driver.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>ID Tugas</th>
                        <th>Order ID</th>
                        <th>Pelanggan</th>
                        <th>Driver</th>
                        <th>Tipe Tugas</th>
                        <th>Tanggal Tugas</th>
                        <th>Status</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengirimans as $pengiriman)
                        <tr>
                            <td><strong>#{{ $pengiriman->id_pengiriman }}</strong></td>
                            <td>
                                <a href="{{ route('admin.order.show', $pengiriman->order->id_order) }}" class="order-link">#{{ $pengiriman->order->id_order }}</a>
                            </td>
                            <td>
                                <div style="color: var(--text-primary); font-weight: 500;">{{ $pengiriman->order->pelanggan->nama }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-secondary);">{{ $pengiriman->order->pelanggan->no_hp }}</div>
                            </td>
                            <td>
                                <div class="driver-badge">
                                    <i class="fa-solid fa-user-gear"></i>
                                    <span>{{ $pengiriman->driver->nama }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="tipe-badge {{ $pengiriman->tipe_tugas === 'Antar' ? 'tipe-badge-antar' : 'tipe-badge-jemput' }}">
                                    {{ $pengiriman->tipe_tugas }}
                                </span>
                            </td>
                            <td>
                                <span class="date-badge">{{ $pengiriman->tgl_jadwal->format('d M Y') }}</span>
                            </td>
                            <td>
                                @if($pengiriman->status_tugas === 'pending')
                                    <span class="badge badge-pending">Pending</span>
                                @elseif($pengiriman->status_tugas === 'proses')
                                    <span class="badge badge-process">Sedang Proses</span>
                                @elseif($pengiriman->status_tugas === 'accepted')
                                    <span class="badge badge-accepted">Tugas Diterima</span>
                                @elseif($pengiriman->status_tugas === 'pickup')
                                    <span class="badge badge-pickup">Muat Barang</span>
                                @elseif($pengiriman->status_tugas === 'on_the_way')
                                    <span class="badge badge-ontheway">Dalam Perjalanan</span>
                                @elseif($pengiriman->status_tugas === 'arrived')
                                    <span class="badge badge-arrived">Sampai Lokasi</span>
                                @elseif($pengiriman->status_tugas === 'selesai' || $pengiriman->status_tugas === 'done')
                                    <span class="badge badge-success">Selesai</span>
                                @elseif($pengiriman->status_tugas === 'cancelled')
                                    <span class="badge badge-cancelled">Dibatalkan</span>
                                @else
                                    <span class="badge badge-pending">{{ $pengiriman->status_tugas }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="catatan" title="{{ $pengiriman->catatan_kondisi_alat }}">
                                    {{ $pengiriman->catatan_kondisi_alat ?? '-' }}
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
