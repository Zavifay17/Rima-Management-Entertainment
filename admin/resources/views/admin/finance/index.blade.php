@extends('layouts.admin')

@section('title', 'Laporan Keuangan')

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

    .stat-card {
        background: linear-gradient(135deg, #10b981, #059669);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        color: white;
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.2);
        margin-bottom: 2.5rem;
        display: inline-block;
        min-width: 300px;
    }

    .stat-card h5 {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        opacity: 0.9;
    }

    .stat-card h2 {
        font-size: 2.25rem;
        font-weight: 800;
        margin-bottom: 0.25rem;
        font-family: 'Outfit', sans-serif;
    }

    .stat-card small {
        font-size: 0.85rem;
        opacity: 0.8;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        border-radius: 8px;
    }

    /* Print Header specifically for RME Branding */
    .print-header {
        display: none;
    }

    @media print {
        @page {
            size: landscape;
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
        .print-logo span:nth-child(3) { color: var(--accent-secondary); }

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
            color: var(--accent-primary);
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

        .page-header { display: none !important; }
        .glass-card { border: none !important; padding: 0 !important; box-shadow: none !important; }
        .stat-card { box-shadow: none !important; }
        
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
        <h2>Laporan Keuangan</h2>
        <p>Dicetak pada: {{ now()->format('d M Y, H:i') }}</p>
    </div>
</div>

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
    <div class="page-title">
        <h1>Laporan Keuangan</h1>
        <p>Rekapitulasi pendapatan dari pesanan yang telah selesai</p>
    </div>
    <button onclick="window.print()" class="btn btn-primary">
        <i class="fa-solid fa-print"></i> Cetak Laporan
    </button>
</div>

<div class="stat-card">
    <h5>Total Pendapatan</h5>
    <h2>Rp {{ number_format($totalIncome, 0, ',', '.') }}</h2>
    <small>Dari {{ $completedOrders->count() }} pesanan selesai</small>
</div>

<div class="glass-card">
    <h3 style="font-family: 'Outfit', sans-serif; font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem; color: var(--text-primary);">Daftar Transaksi Selesai</h3>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID Order</th>
                    <th>Tanggal Selesai</th>
                    <th>Nama Pelanggan</th>
                    <th>Total Harga</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($completedOrders as $order)
                <tr>
                    <td><strong>#{{ $order->id_order }}</strong></td>
                    <td>{{ $order->updated_at->format('d M Y') }}</td>
                    <td>{{ $order->nama_pelanggan }}</td>
                    <td><span class="price-badge"><span class="rp-label">Rp</span><span class="rp-amount">{{ number_format($order->total_harga, 0, ',', '.') }}</span></span></td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.order.show', $order->id_order) }}" class="btn-detail" title="Lihat Detail Pesanan">
                            <i class="fas fa-magnifying-glass"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center" style="text-align: center; padding: 3rem 0;">Belum ada transaksi yang selesai.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
