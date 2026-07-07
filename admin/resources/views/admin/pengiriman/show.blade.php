@extends('layouts.admin')

@section('title', 'Detail Tugas Driver #' . $pengiriman->id_pengiriman)

@section('styles')
<style>
    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 2rem;
    }

    @media (max-width: 992px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }

    .info-section {
        margin-bottom: 2rem;
    }

    .info-section h3 {
        color: var(--text-primary);
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-section h3 i {
        color: var(--accent-primary);
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
    }

    .info-label {
        color: var(--text-secondary);
    }

    .info-value {
        color: var(--text-primary);
        font-weight: 500;
    }

    .table-details {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .table-details th {
        background: rgba(0, 0, 0, 0.01);
        color: var(--text-primary);
        font-weight: 600;
        padding: 0.75rem 1rem;
        font-size: 0.85rem;
        text-transform: uppercase;
        border-bottom: 1px solid var(--border-color);
    }

    .table-details td {
        padding: 0.85rem 1rem;
        font-size: 0.9rem;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border-color);
    }

    .table-details tr:hover td {
        color: var(--text-primary);
        background: rgba(0, 0, 0, 0.01);
    }

    /* Photo documentation gallery styles */
    .photo-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1.25rem;
        margin-top: 1rem;
    }

    .photo-card {
        background: rgba(0, 0, 0, 0.01);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .photo-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    .photo-img {
        width: 100%;
        height: 140px;
        object-fit: cover;
    }

    .photo-placeholder {
        height: 140px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: rgba(99, 102, 241, 0.04);
        padding: 1rem;
        text-align: center;
    }

    .photo-name {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-primary);
        word-break: break-all;
        margin-top: 0.5rem;
    }

    .photo-path {
        font-size: 0.65rem;
        color: var(--text-secondary);
        margin-top: 0.25rem;
        word-break: break-all;
    }

    .photo-footer {
        padding: 0.75rem 1rem;
        background: #ffffff;
        border-top: 1px solid var(--border-color);
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--text-secondary);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <a href="{{ route('admin.pengiriman.index') }}" style="color: var(--text-secondary); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; margin-bottom: 0.75rem; transition: color 0.3s;" onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-secondary)'">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
        </a>
        <h1>Detail Tugas Driver #{{ $pengiriman->id_pengiriman }}</h1>
        <p>Detail status tugas driver logistik untuk pengantaran atau penjemputan alat sewa.</p>
    </div>
</div>

<div class="detail-grid">
    <!-- Kolom Kiri: Info Tugas & Pihak Terlibat -->
    <div class="left-col">
        <div class="glass-card">
            <!-- Status Tugas -->
            <div class="info-section">
                <h3><i class="fa-solid fa-circle-info"></i> Informasi Status</h3>
                <div class="info-row">
                    <span class="info-label">Tipe Tugas:</span>
                    <span class="info-value">
                        <span class="badge {{ $pengiriman->tipe_tugas === 'Antar' ? 'tipe-badge-antar' : 'tipe-badge-jemput' }}" style="padding: 0.25rem 0.6rem; font-size: 0.8rem;">
                            {{ $pengiriman->tipe_tugas === 'Antar' ? 'Antar Alat' : 'Jemput Alat' }}
                        </span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status Tugas:</span>
                    <span class="info-value">
                        @if($pengiriman->status_tugas === 'pending')
                            @if(!$pengiriman->id_driver)
                                <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; border-color: rgba(245, 158, 11, 0.2); font-weight: 600;">Menunggu Klaim</span>
                            @else
                                <span class="badge badge-pending">Pending (Menunggu)</span>
                            @endif
                        @elseif($pengiriman->status_tugas === 'proses')
                            <span class="badge badge-process">Sedang Proses</span>
                        @elseif($pengiriman->status_tugas === 'accepted')
                            <span class="badge badge-process" style="background: rgba(99, 102, 241, 0.1); color: #6366f1; border-color: rgba(99, 102, 241, 0.2);"><i class="fa-solid fa-clipboard-check"></i> Tugas Diterima</span>
                        @elseif($pengiriman->status_tugas === 'pickup')
                            <span class="badge badge-process" style="background: rgba(6, 182, 212, 0.1); color: #06b6d4; border-color: rgba(6, 182, 212, 0.2);"><i class="fa-solid fa-truck-ramp-box"></i> Muat Barang</span>
                        @elseif($pengiriman->status_tugas === 'on_the_way')
                            <span class="badge badge-process" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6; border-color: rgba(59, 130, 246, 0.2);"><i class="fa-solid fa-truck-fast"></i> Dalam Perjalanan</span>
                        @elseif($pengiriman->status_tugas === 'arrived')
                            <span class="badge badge-process" style="background: rgba(236, 72, 153, 0.1); color: #ec4899; border-color: rgba(236, 72, 153, 0.2);"><i class="fa-solid fa-location-dot"></i> Sampai Lokasi</span>
                        @elseif($pengiriman->status_tugas === 'selesai' || $pengiriman->status_tugas === 'done')
                            <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> Selesai</span>
                        @elseif($pengiriman->status_tugas === 'cancelled')
                            <span class="badge badge-pending" style="background: rgba(239, 68, 68, 0.1); color: var(--danger); border-color: rgba(239, 68, 68, 0.2);"><i class="fa-solid fa-circle-xmark"></i> Dibatalkan</span>
                        @else
                            <span class="badge badge-pending">{{ $pengiriman->status_tugas }}</span>
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Pelaksanaan:</span>
                    <span class="info-value">{{ $pengiriman->tgl_jadwal->format('d F Y') }}</span>
                </div>
            </div>

            <!-- Driver Info -->
            <div class="info-section">
                <h3><i class="fa-solid fa-user-gear"></i> Informasi Driver</h3>
                @if($pengiriman->driver)
                    <div class="info-row">
                        <span class="info-label">Nama Driver:</span>
                        <span class="info-value">{{ $pengiriman->driver->nama }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Nomor WhatsApp:</span>
                        <span class="info-value" style="color: #10b981; font-weight: 600;">
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pengiriman->driver->no_hp) }}" target="_blank" style="color: inherit; text-decoration: none;">
                                <i class="fa-brands fa-whatsapp"></i> {{ $pengiriman->driver->no_hp }}
                            </a>
                        </span>
                    </div>
                @else
                    <div style="background: rgba(245, 158, 11, 0.05); border: 1px dashed rgba(245, 158, 11, 0.3); border-radius: 12px; padding: 1rem; text-align: center;">
                        <i class="fa-solid fa-users-viewfinder" style="font-size: 1.5rem; color: #f59e0b; margin-bottom: 0.5rem; display: block;"></i>
                        <span style="color: #d97706; font-weight: 600; font-size: 0.9rem;">Menunggu Diambil Driver</span>
                        <p style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem; margin-bottom: 0;">Tugas ini berstatus terbuka dan dapat dipilih langsung oleh semua driver aktif lewat aplikasi mereka.</p>
                    </div>
                @endif
            </div>

            <!-- Customer Info -->
            <div class="info-section" style="margin-bottom: 0;">
                <h3><i class="fa-solid fa-user"></i> Informasi Pelanggan</h3>
                <div class="info-row">
                    <span class="info-label">Nama Pelanggan:</span>
                    <span class="info-value">{{ $pengiriman->order->pelanggan->nama }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nomor WhatsApp:</span>
                    <span class="info-value" style="color: #a855f7;">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pengiriman->order->pelanggan->no_hp) }}" target="_blank" style="color: inherit; text-decoration: none;">
                            <i class="fa-brands fa-whatsapp"></i> {{ $pengiriman->order->pelanggan->no_hp }}
                        </a>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Order ID:</span>
                    <span class="info-value">
                        <a href="{{ route('admin.order.show', $pengiriman->order->id_order) }}" class="order-link">#{{ $pengiriman->order->id_order }}</a>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Rincian Alat & Bukti Foto -->
    <div class="right-col">
        <!-- Rincian Alat Event -->
        <div class="glass-card" style="margin-bottom: 2rem;">
            <div class="info-section" style="margin-bottom: 0;">
                <h3><i class="fa-solid fa-toolbox"></i> Daftar Alat / Barang Event</h3>
                <table class="table-details">
                    <thead>
                        <tr>
                            <th>Nama Paket / Barang</th>
                            <th>Kategori</th>
                            <th style="text-align: center;">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengiriman->order->orderDetails as $detail)
                            <tr>
                                <td>
                                    <div style="color: var(--text-primary); font-weight: 500;">
                                        {{ $detail->layananSewa ? $detail->layananSewa->nama_layanan : 'Paket Alat' }}
                                    </div>
                                </td>
                                <td>{{ $detail->layananSewa ? $detail->layananSewa->kategori : 'Alat Event' }}</td>
                                <td style="text-align: center; color: var(--text-primary); font-weight: 600;">{{ $detail->kuantitas }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($pengiriman->catatan_kondisi_alat)
                    <div style="margin-top: 1.5rem; padding: 1rem; background: rgba(0, 0, 0, 0.02); border-left: 4px solid var(--accent-primary); border-radius: 4px 12px 12px 4px; font-size: 0.9rem;">
                        <strong>Catatan Khusus / Instruksi:</strong>
                        <p style="margin-top: 0.25rem; color: var(--text-secondary); margin-bottom: 0; line-height: 1.5;">{{ $pengiriman->catatan_kondisi_alat }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Bukti Foto Dokumentasi -->
        <div class="glass-card">
            <div class="info-section" style="margin-bottom: 0;">
                <h3><i class="fa-solid fa-camera"></i> Bukti Dokumentasi Lapangan</h3>
                
                @if(empty($photos))
                    <div style="text-align: center; padding: 3rem 1rem; color: var(--text-secondary);">
                        <i class="fa-solid fa-image" style="font-size: 3rem; color: var(--border-color); margin-bottom: 1rem; display: block;"></i>
                        <p style="font-size: 0.95rem; margin-bottom: 0;">Belum ada bukti foto yang diunggah oleh driver.</p>
                        <p style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 0.25rem;">Foto dokumentasi akan muncul di sini secara real-time setelah diunggah via aplikasi HP driver.</p>
                    </div>
                @else
                    <p style="font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 1rem;">
                        Berikut adalah <strong>{{ count($photos) }} foto bukti dokumentasi</strong> ber-watermark yang telah diunggah oleh driver:
                    </p>
                    <div class="photo-gallery">
                        @foreach($photos as $index => $photo)
                            <div class="photo-card">
                                @if(str_starts_with($photo, 'http') || str_starts_with($photo, 'data:'))
                                    <img src="{{ $photo }}" alt="Bukti Foto #{{ $index + 1 }}" class="photo-img" onclick="window.open(this.src, '_blank')">
                                @else
                                    <div class="photo-placeholder" title="{{ $photo }}">
                                        <i class="fa-solid fa-camera-retro" style="font-size: 2.25rem; color: var(--accent-primary); margin-bottom: 0.5rem;"></i>
                                        <div class="photo-name">{{ basename($photo) }}</div>
                                    </div>
                                @endif
                                <div class="photo-footer">
                                    <span>Foto #{{ $index + 1 }}</span>
                                    @if(!str_starts_with($photo, 'http'))
                                        <span class="badge" style="background: rgba(99, 102, 241, 0.08); color: #6366f1; border-color: rgba(99, 102, 241, 0.18); font-size: 0.65rem;">Local Sync</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
