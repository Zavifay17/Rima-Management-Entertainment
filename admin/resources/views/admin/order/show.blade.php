@extends('layouts.admin')

@section('title', 'Detail Pemesanan #' . $order->id_order)

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

    .total-container {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 1.5rem;
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 2px dashed var(--border-color);
    }

    .total-label {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .total-val {
        font-family: 'Courier New', Courier, monospace;
        font-size: 1.5rem;
        font-weight: 700;
        color: #10b981;
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

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml;utf8,<svg fill='%23475569' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/><path d='M0 0h24v24H0z' fill='none'/></svg>");
        background-repeat: no-repeat;
        background-position: right 10px center;
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

    .driver-log-card {
        background: rgba(0, 0, 0, 0.01);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <a href="{{ route('admin.order.index') }}" style="color: var(--text-secondary); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; margin-bottom: 0.75rem; transition: color 0.3s;" onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-secondary)'">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
        </a>
        <h1>Detail Pemesanan #{{ $order->id_order }}</h1>
        <p>Kelola status penyewaan dan alokasi logistik driver untuk pesanan ini.</p>
    </div>
</div>

<div class="detail-grid">
    <!-- Kolom Kiri: Informasi Pelanggan & Status -->
    <div class="left-col">
        <div class="glass-card">
            <div class="info-section">
                <h3><i class="fa-solid fa-circle-info"></i> Status Pemesanan</h3>
                <div class="info-row">
                    <span class="info-label">Status Saat Ini:</span>
                    <span class="info-value">
                        @php
                            $statusLower = strtolower($order->status_sewa);
                        @endphp
                        @if($statusLower === 'pending')
                            <span class="badge badge-pending">Pending</span>
                        @elseif($statusLower === 'disetujui')
                            <span class="badge badge-success" style="background: rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.3); color: var(--success);"><i class="fa-solid fa-circle-check"></i> Disetujui</span>
                        @elseif($statusLower === 'diproses')
                            <span class="badge badge-process"><i class="fa-solid fa-arrows-spin"></i> Diproses</span>
                        @elseif($statusLower === 'selesai')
                            <span class="badge badge-success"><i class="fa-solid fa-calendar-check"></i> Selesai</span>
                        @elseif($statusLower === 'dibatalkan' || $statusLower === 'batal')
                            <span class="badge badge-pending" style="background: rgba(239, 68, 68, 0.15); border-color: rgba(239, 68, 68, 0.3); color: var(--danger);"><i class="fa-solid fa-circle-xmark"></i> Batal</span>
                        @else
                            <span class="badge badge-pending">{{ $order->status_sewa }}</span>
                        @endif
                    </span>
                </div>
            </div>

            <!-- Form Perbarui Status -->
            <form action="{{ route('admin.order.update-status', $order->id_order) }}" method="POST" class="info-section">
                @csrf
                <div class="form-group">
                    <label for="status_sewa">Perbarui Status Sewa</label>
                    <select name="status_sewa" id="status_sewa" class="form-control" required>
                        <option value="Pending" {{ $order->status_sewa == 'Pending' || $order->status_sewa == 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                        <option value="Disetujui" {{ $order->status_sewa == 'Disetujui' || $order->status_sewa == 'disetujui' ? 'selected' : '' }}>Disetujui (Konfirmasi Ulang)</option>
                        <option value="Diproses" {{ $order->status_sewa == 'Diproses' || $order->status_sewa == 'diproses' ? 'selected' : '' }}>Diproses (Dalam Pengerjaan)</option>
                        <option value="Selesai" {{ $order->status_sewa == 'Selesai' || $order->status_sewa == 'selesai' ? 'selected' : '' }}>Selesai (Acara Rampung)</option>
                        <option value="Dibatalkan" {{ $order->status_sewa == 'Dibatalkan' || $order->status_sewa == 'dibatalkan' || $order->status_sewa == 'batal' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fa-solid fa-pen-to-square"></i> Perbarui Status
                </button>
            </form>

            <div class="info-section" style="margin-top: 2rem;">
                <h3><i class="fa-solid fa-user"></i> Informasi Pelanggan</h3>
                <div class="info-row">
                    <span class="info-label">Nama Pelanggan:</span>
                    <span class="info-value">{{ $order->pelanggan->nama }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nomor WhatsApp:</span>
                    <span class="info-value" style="color: #a855f7;">{{ $order->pelanggan->no_hp }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Username (Email):</span>
                    <span class="info-value">{{ $order->pelanggan->username }}</span>
                </div>
                
                <button class="btn btn-success" style="width: 100%; margin-top: 1rem;" onclick="openWhatsAppModal()">
                    <i class="fa-brands fa-whatsapp"></i> Konfirmasi via WA
                </button>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Rincian Sewa & Pengiriman -->
    <div class="right-col">
        <div class="glass-card">
            <div class="info-section">
                <h3><i class="fa-solid fa-receipt"></i> Rincian Paket Penyewaan</h3>
                <div class="info-row">
                    <span class="info-label">Tanggal Sewa Mulai:</span>
                    <span class="info-value">{{ $order->tgl_mulai->format('d F Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Sewa Selesai:</span>
                    <span class="info-value">{{ $order->tgl_selesai->format('d F Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Durasi Sewa:</span>
                    <span class="info-value">
                        @php
                            $tglMulai = \Carbon\Carbon::parse($order->tgl_mulai);
                            $tglSelesai = \Carbon\Carbon::parse($order->tgl_selesai);
                            $durasi = $tglMulai->diffInDays($tglSelesai) + 1;
                        @endphp
                        {{ $durasi }} Hari
                    </span>
                </div>

                <table class="table-details">
                    <thead>
                        <tr>
                            <th>Nama Paket / Barang</th>
                            <th>Kategori</th>
                            <th style="text-align: center;">Jumlah</th>
                            <th style="text-align: right;">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderDetails as $detail)
                            <tr>
                                <td>
                                    <div style="color: var(--text-primary); font-weight: 500;">
                                        {{ $detail->layananSewa ? $detail->layananSewa->nama_layanan : 'Paket Sewa Alat' }}
                                    </div>
                                </td>
                                <td>{{ $detail->layananSewa ? $detail->layananSewa->kategori : 'Event' }}</td>
                                <td style="text-align: center; color: var(--text-primary);">{{ $detail->kuantitas }}</td>
                                <td style="text-align: right; color: var(--text-primary); font-family: monospace;">
                                    Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="total-container" style="flex-direction: column; align-items: flex-end; gap: 0.5rem;">
                    <div style="display: flex; gap: 1.5rem; align-items: center;">
                        <span class="total-label">Total Pembayaran:</span>
                        <span class="total-val">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                    </div>
                    <div style="display: flex; gap: 1.5rem; align-items: center; font-size: 0.95rem; color: var(--text-secondary);">
                        <span>Uang Muka / DP 50%:</span>
                        <span style="font-weight: 700; color: var(--accent-primary);">Rp {{ number_format($order->dp_minimum, 0, ',', '.') }}</span>
                    </div>
                    <div style="display: flex; gap: 1.5rem; align-items: center; font-size: 0.95rem; color: var(--text-secondary);">
                        <span>Sisa Pelunasan:</span>
                        <span style="font-weight: 700; color: var(--text-primary);">Rp {{ number_format($order->sisa_pembayaran, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-card">
            <div class="info-section" style="margin-bottom: 0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">
                    <h3 style="border-bottom: none; margin-bottom: 0; padding-bottom: 0;"><i class="fa-solid fa-truck-ramp-box"></i> Log Pengiriman Driver</h3>
                    <a href="{{ route('admin.pengiriman.create', ['id_order' => $order->id_order]) }}" class="btn btn-outline btn-sm" style="padding: 0.35rem 0.75rem; font-size: 0.8rem;">
                        <i class="fa-solid fa-plus"></i> Alokasikan Driver
                    </a>
                </div>

                @if($order->pengirimans->isEmpty())
                    <p style="color: var(--text-secondary); text-align: center; padding: 1.5rem 0; font-size: 0.95rem;">
                        Belum ada tugas pengantaran/penjemputan yang ditugaskan ke driver.
                    </p>
                @else
                    @foreach($order->pengirimans as $pengiriman)
                        <div class="driver-log-card">
                            <div>
                                <div style="color: var(--text-primary); font-weight: 600;">
                                    <span class="badge {{ $pengiriman->tipe_tugas === 'Antar' ? 'tipe-badge-antar' : 'tipe-badge-jemput' }}" style="padding: 0.15rem 0.5rem; font-size: 0.75rem; margin-right: 0.5rem; display: inline-flex;">
                                        {{ $pengiriman->tipe_tugas }}
                                    </span>
                                    {{ $pengiriman->driver->nama }}
                                </div>
                                <div style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 0.25rem;">
                                    Jadwal: {{ $pengiriman->tgl_jadwal->format('d M Y') }}
                                </div>
                            </div>
                            <div>
                                @if($pengiriman->status_tugas === 'pending')
                                    <span class="badge badge-pending">Pending</span>
                                @elseif($pengiriman->status_tugas === 'proses')
                                    <span class="badge badge-process">Proses</span>
                                @elseif($pengiriman->status_tugas === 'selesai')
                                    <span class="badge badge-success">Selesai</span>
                                @else
                                    <span class="badge badge-pending">{{ $pengiriman->status_tugas }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
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

    function openWhatsAppModal() {
        fetch(`/admin/order/{{ $order->id_order }}/wa-template`)
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
