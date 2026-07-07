@extends('layouts.admin')

@section('title', 'Laporan Pemesanan & Lokasi')

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
        table-layout: fixed; /* Ensures columns respect their widths */
    }

    /* RME Logo Colors Integration */
    th {
        background: rgba(0, 0, 128, 0.04); /* Very light Navy Blue tint */
        color: var(--accent-primary); /* Navy Blue text */
        font-weight: 700;
        padding: 1.15rem 1rem;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid var(--accent-primary); /* Solid Navy Blue bottom border */
    }

    /* Explicit Column Widths to prevent cramping */
    th:nth-child(1) { width: 12%; } /* Tanggal */
    th:nth-child(2) { width: 18%; } /* Pelanggan */
    th:nth-child(3) { width: 15%; } /* Jadwal */
    th:nth-child(4) { width: 15%; } /* Harga */
    th:nth-child(5) { width: 10%; } /* Status */
    th:nth-child(6) { width: 30%; } /* Lokasi - Maximum space to prevent wrapping */

    td {
        padding: 1.25rem 1rem;
        font-size: 0.9rem;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border-color);
        vertical-align: top;
        word-wrap: break-word; /* Handle long text */
    }

    tr:hover td {
        background: rgba(0, 0, 128, 0.02);
    }

    .address-box {
        font-size: 0.85rem;
        color: var(--text-secondary);
        line-height: 1.5;
        width: 100%;
    }
    
    .status-text {
        font-weight: 700;
        letter-spacing: 0.02em;
    }

    /* Print Header specifically for RME Branding */
    .print-header {
        display: none;
    }

    @media print {
        @page {
            size: landscape; /* Wide orientation prevents narrow columns */
            margin: 15mm;
        }

        .print-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid var(--accent-primary);
            padding-bottom: 1.5rem;
            margin-bottom: 2rem;
        }

        .print-logo-wrap {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .print-logo {
            font-size: 2.8rem;
            font-weight: 900;
            font-family: 'Outfit', sans-serif;
            font-style: italic;
            letter-spacing: -1px;
            line-height: 1;
        }
        
        .print-logo span:nth-child(1) { color: var(--accent-primary); letter-spacing: -3px; }
        .print-logo span:nth-child(2) { color: var(--accent-primary); }
        .print-logo span:nth-child(3) { color: var(--accent-secondary); } /* RME Red */

        .print-brand-name {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 800;
            color: #000;
            line-height: 1.1;
            text-transform: uppercase;
        }

        .print-brand-name span {
            display: block;
            font-size: 0.65rem;
            letter-spacing: 2px;
        }

        .print-title {
            text-align: right;
        }

        .print-title h2 {
            color: var(--accent-primary); /* Navy Blue */
            font-size: 1.5rem;
            margin-bottom: 0.2rem;
            text-transform: uppercase;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .print-title p {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        /* Hide elements that shouldn't be printed */
        .page-header { display: none !important; }
        .glass-card { border: none !important; padding: 0 !important; }
        
        /* Force browser to print colors properly */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>
@endsection

@section('content')
<!-- Print Header (Hidden on screen) -->
<div class="print-header">
    <div class="print-logo-wrap">
        <div class="print-logo">
            <span>R</span><span>M</span><span>E</span>
        </div>
        <div class="print-brand-name">
            RME<br><span>ENTERTAINMENT</span>
        </div>
    </div>
    <div class="print-title">
        <h2>Laporan Pemesanan</h2>
        <p>Dicetak pada: {{ now()->format('d M Y, H:i') }}</p>
    </div>
</div>

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
    <div class="page-title">
        <h1>Laporan Pemesanan</h1>
        <p>Riwayat pesanan dari yang terbaru beserta lokasi acara</p>
    </div>
    <button onclick="window.print()" class="btn btn-primary" style="background: linear-gradient(135deg, var(--accent-primary), #1a1a9e);">
        <i class="fa-solid fa-print"></i> Cetak Laporan
    </button>
</div>

<div class="glass-card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Tanggal Order</th>
                    <th>Pelanggan</th>
                    <th>Jadwal Sewa</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Lokasi / Alamat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>
                        <strong style="color: var(--accent-primary);">{{ $order->created_at ? $order->created_at->format('d M Y') : '-' }}</strong><br>
                        <small style="opacity: 0.6; font-family: monospace; font-size: 0.8rem;">#{{ $order->id_order }}</small>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: var(--text-primary); margin-bottom: 2px;">{{ $order->nama_pelanggan }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-secondary);">
                            <i class="fa-solid fa-phone" style="font-size: 0.7rem; color: var(--accent-primary); opacity: 0.7;"></i> 
                            {{ $order->no_hp_pelanggan }}
                        </div>
                    </td>
                    <td>
                        <div style="margin-bottom: 2px;">{{ $order->tgl_mulai->format('d/m/Y') }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-secondary);">s/d {{ $order->tgl_selesai->format('d/m/Y') }}</div>
                    </td>
                    <td>
                        <span style="font-weight: 800; color: #10b981;">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                    </td>
                    <td>
                        @php
                            $status = strtolower($order->status_sewa);
                        @endphp
                        @if($status === 'selesai')
                            <span class="status-text" style="color: #10b981;">Selesai</span>
                        @elseif($status === 'dibatalkan' || $status === 'batal')
                            <span class="status-text" style="color: var(--accent-secondary);">Batal</span>
                        @else
                            <span class="status-text" style="color: #f59e0b;">{{ ucfirst($order->status_sewa) }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="address-box">
                            @if($order->lokasi_alamat)
                                <i class="fa-solid fa-location-dot" style="color: var(--accent-secondary); float: left; margin-right: 6px; margin-top: 2px;"></i>
                                <span style="display: block; overflow: hidden;">{{ $order->lokasi_alamat }}</span>
                            @else
                                <span style="opacity: 0.5; font-style: italic;">Belum ditentukan</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 4rem 0;">
                        <i class="fa-solid fa-folder-open" style="font-size: 2.5rem; color: var(--border-color); margin-bottom: 1rem; display: block;"></i>
                        Belum ada pesanan terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
