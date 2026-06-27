@extends('layouts.admin')

@section('title', 'Direktori Pelanggan')

@section('styles')
<style>
    .search-bar {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        max-width: 600px;
    }

    .search-input {
        flex: 1;
        background: rgba(255, 255, 255, 0.6);
        border: 1px solid rgba(59, 130, 246, 0.15);
        border-radius: 12px;
        padding: 0.75rem 1.2rem;
        color: var(--text-primary);
        font-size: 0.95rem;
        transition: all 0.3s;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--accent-primary);
        background: #ffffff;
        box-shadow: 0 0 10px rgba(59, 130, 246, 0.1);
    }

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

    .customer-name {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .customer-email {
        font-size: 0.8rem;
        color: var(--text-secondary);
        margin-top: 0.15rem;
    }

    .phone-badge {
        font-family: 'Courier New', Courier, monospace;
        color: var(--accent-primary);
        font-weight: 600;
        background: rgba(59, 130, 246, 0.06);
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        border: 1px solid rgba(59, 130, 246, 0.12);
        white-space: nowrap;
    }

    .order-count {
        background: rgba(16, 185, 129, 0.06);
        color: var(--success);
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        font-weight: 700;
        border: 1px solid rgba(16, 185, 129, 0.12);
        display: inline-block;
    }

    .spent-badge {
        font-family: monospace;
        font-weight: 700;
        color: var(--text-primary);
        font-size: 1rem;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        border-radius: 8px;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Direktori Pelanggan</h1>
        <p>Lihat data profil pelanggan, riwayat kuantitas sewa, dan akumulasi nilai sewa.</p>
    </div>
</div>

<form action="{{ route('admin.pelanggan.index') }}" method="GET" class="search-bar">
    <input type="text" name="search" class="search-input" placeholder="Cari nama, email, atau nomor HP pelanggan..." value="{{ $search }}">
    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
    @if($search)
        <a href="{{ route('admin.pelanggan.index') }}" class="btn btn-outline"><i class="fa-solid fa-xmark"></i> Bersihkan</a>
    @endif
</form>

<div class="glass-card">
    <div class="table-container">
        @if($customers->isEmpty())
            <div style="text-align: center; padding: 3rem 0; color: var(--text-secondary);">
                <i class="fa-solid fa-users-slash" style="font-size: 3rem; margin-bottom: 1rem; color: var(--border-color);"></i>
                <p>Tidak ada data pelanggan yang cocok dengan pencarian Anda.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Pelanggan</th>
                        <th>Nomor WhatsApp</th>
                        <th>Jumlah Sewa</th>
                        <th>Total Pengeluaran</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                        <tr>
                            <td>
                                <div class="layanan-info">
                                    <span class="customer-name">{{ $customer->nama }}</span>
                                    <span class="customer-email">{{ $customer->email }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="phone-badge">{{ $customer->no_hp }}</span>
                            </td>
                            <td>
                                <span class="order-count">{{ $customer->total_orders }} Kali</span>
                            </td>
                            <td>
                                <span class="spent-badge">Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                                    @php
                                        // Sanitasi Nomor HP untuk link WhatsApp
                                        $phone = preg_replace('/[^0-9]/', '', $customer->no_hp);
                                        if (str_starts_with($phone, '0')) {
                                            $phone = '62' . substr($phone, 1);
                                        }
                                    @endphp
                                    <a href="https://wa.me/{{ $phone }}" target="_blank" class="btn btn-success btn-sm">
                                        <i class="fa-brands fa-whatsapp"></i> Hubungi WA
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
@endsection
