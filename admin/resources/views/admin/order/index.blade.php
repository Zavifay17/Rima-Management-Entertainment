@extends('layouts.admin')

@section('title', 'Daftar Pemesanan')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
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

    .order-info {
        display: flex;
        flex-direction: column;
    }

    .order-id {
        font-weight: 700;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .pelanggan-nama {
        font-weight: 600;
        color: var(--accent-primary);
    }

    .pelanggan-hp {
        font-size: 0.8rem;
        color: var(--text-secondary);
    }

    /* ===== PRICE BADGE - Bubble Font ===== */
    .price-badge {
        font-family: 'Nunito', sans-serif;
        font-weight: 800;
        color: #059669;
        display: inline-flex;
        align-items: baseline;
        gap: 4px;
    }

    .price-badge .rp-label {
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        background: linear-gradient(135deg, #10b981, #059669);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .price-badge .rp-amount {
        font-size: 1.05rem;
        font-weight: 900;
        letter-spacing: -0.01em;
        background: linear-gradient(135deg, #10b981, #059669);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* ===== DATE RANGE - Chip Style ===== */
    .date-range {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(99, 102, 241, 0.08);
        border: 1px solid rgba(99, 102, 241, 0.2);
        border-radius: 999px;
        padding: 0.35rem 0.85rem;
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-primary);
        white-space: nowrap;
    }

    .date-range i {
        color: #6366f1;
        font-size: 0.8rem;
    }

    .date-separator {
        color: var(--text-secondary);
        font-weight: 400;
        margin: 0 2px;
    }

    /* ===== BADGE STATUS ===== */
    .badge-disetujui {
        background: rgba(16, 185, 129, 0.15);
        color: var(--success);
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .badge-dibatalkan {
        background: rgba(239, 68, 68, 0.15);
        color: var(--danger);
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    /* ===== ACTION BUTTONS ===== */
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

    /* Detail button - abu-abu elegan */
    .btn-detail {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 1.1rem;
        font-size: 0.83rem;
        font-weight: 600;
        border-radius: 10px;
        background: #f1f1f3;
        color: #4b5563;
        border: 1px solid #d1d5db;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
    }

    .btn-detail:hover {
        background: #e5e7eb;
        color: #1f2937;
        border-color: #9ca3af;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        transform: translateY(-1px);
    }

    .btn-detail i {
        font-size: 0.78rem;
        opacity: 0.7;
    }

    /* Modal Glassmorphic Styling */
    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(8px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .modal-backdrop.show {
        display: flex;
        opacity: 1;
    }

    .modal-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 24px;
        width: 100%;
        max-width: 550px;
        padding: 2.25rem;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }

    .modal-backdrop.show .modal-card {
        transform: scale(1);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .modal-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-close {
        background: none;
        border: none;
        color: var(--text-secondary);
        font-size: 1.25rem;
        cursor: pointer;
        transition: color 0.3s;
    }

    .modal-close:hover {
        color: var(--text-primary);
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        background: rgba(0, 0, 0, 0.02);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 0.75rem 1rem;
        color: var(--text-primary);
        font-size: 0.95rem;
        transition: all 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--accent-primary);
        background: rgba(0, 0, 0, 0.04);
        box-shadow: 0 0 10px rgba(99, 102, 241, 0.15);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 120px;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        margin-top: 1.75rem;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Pemrosesan Hasil Pemesanan</h1>
        <p>Kelola pemesanan penyewaan alat event dari pelanggan yang masuk melalui Landing Page.</p>
    </div>
</div>

<div class="glass-card">
    <div class="table-container">
        @if($orders->isEmpty())
            <div style="text-align: center; padding: 3rem 0; color: var(--text-secondary);">
                <i class="fa-solid fa-folder-open" style="font-size: 3rem; margin-bottom: 1rem; color: var(--border-color);"></i>
                <p>Belum ada data pemesanan yang masuk.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>ID Order</th>
                        <th>Pelanggan</th>
                        <th>Tanggal Sewa</th>
                        <th>Total Harga</th>
                        <th>Status Sewa</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>
                                <span class="order-id">#{{ $order->id_order }}</span>
                            </td>
                            <td>
                                <div class="order-info">
                                    <span class="pelanggan-nama">{{ $order->pelanggan->nama }}</span>
                                    <span class="pelanggan-hp">{{ $order->pelanggan->no_hp }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="date-range">
                                    <i class="fa-regular fa-calendar-days"></i>
                                    {{ $order->tgl_mulai->format('d M Y') }}
                                    <span class="date-separator">→</span>
                                    {{ $order->tgl_selesai->format('d M Y') }}
                                </span>
                            </td>
                            <td>
                                <span class="price-badge">
                                    <span class="rp-label">Rp</span>
                                    <span class="rp-amount">{{ number_format($order->total_harga, 0, ',', '.') }}</span>
                                </span>
                            </td>
                            <td>
                                @php
                                    $statusLower = strtolower($order->status_sewa);
                                @endphp
                                @if($statusLower === 'pending')
                                    <span class="badge badge-pending">Pending</span>
                                @elseif($statusLower === 'disetujui')
                                    <span class="badge badge-disetujui"><i class="fa-solid fa-circle-check"></i> Disetujui</span>
                                @elseif($statusLower === 'diproses')
                                    <span class="badge badge-process"><i class="fa-solid fa-arrows-spin"></i> Diproses</span>
                                @elseif($statusLower === 'selesai')
                                    <span class="badge badge-success"><i class="fa-solid fa-calendar-check"></i> Selesai</span>
                                @elseif($statusLower === 'dibatalkan' || $statusLower === 'batal')
                                    <span class="badge badge-dibatalkan"><i class="fa-solid fa-circle-xmark"></i> Batal</span>
                                @else
                                    <span class="badge badge-pending">{{ $order->status_sewa }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-action-container">
                                    <button class="btn btn-success btn-sm" onclick="openWhatsAppModal('{{ $order->id_order }}')">
                                        <i class="fa-brands fa-whatsapp"></i> Konfirmasi WA
                                    </button>
                                    <a href="{{ route('admin.order.show', $order->id_order) }}" class="btn-detail">
                                        <i class="fa-solid fa-magnifying-glass"></i> Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

<!-- WhatsApp Message Preview Modal -->
<div class="modal-backdrop" id="waOrderModal">
    <div class="modal-card">
        <div class="modal-header">
            <div class="modal-title">
                <i class="fa-brands fa-whatsapp" style="color: #10b981; font-size: 1.5rem;"></i>
                <span>Konfirmasi Pesan via WhatsApp</span>
            </div>
            <button class="modal-close" onclick="closeWhatsAppModal()">&times;</button>
        </div>
        
        <div class="form-group">
            <label for="wa_pelanggan_nama">Nama Pelanggan</label>
            <input type="text" id="wa_pelanggan_nama" class="form-control" readonly>
        </div>
        
        <div class="form-group">
            <label for="wa_pesan">Pratinjau Pesan Konfirmasi (Dapat Diedit)</label>
            <textarea id="wa_pesan" class="form-control" oninput="updateWhatsAppUrl()"></textarea>
        </div>

        <div class="modal-footer">
            <button class="btn btn-outline" onclick="closeWhatsAppModal()">Batal</button>
            <a id="wa_send_btn" href="#" target="_blank" class="btn btn-success">
                <i class="fa-solid fa-paper-plane"></i> Kirim WhatsApp
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let waPhone = "";

    function openWhatsAppModal(id) {
        fetch(`/admin/order/${id}/wa-template`)
            .then(response => response.json())
            .then(res => {
                document.getElementById('wa_pelanggan_nama').value = res.nama_pelanggan;
                document.getElementById('wa_pesan').value = res.pesan;
                waPhone = res.formatted_no_hp;
                
                updateWhatsAppUrl();
                
                const modal = document.getElementById('waOrderModal');
                modal.classList.add('show');
            })
            .catch(err => {
                alert("Gagal memuat template konfirmasi.");
            });
    }

    function closeWhatsAppModal() {
        const modal = document.getElementById('waOrderModal');
        modal.classList.remove('show');
    }

    function updateWhatsAppUrl() {
        const message = document.getElementById('wa_pesan').value;
        const encodedMessage = encodeURIComponent(message);
        const link = `https://wa.me/${waPhone}?text=${encodedMessage}`;
        document.getElementById('wa_send_btn').href = link;
    }
</script>
@endsection
