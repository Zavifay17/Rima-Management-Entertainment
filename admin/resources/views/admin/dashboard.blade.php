@extends('layouts.admin')

@section('title', 'Dasbor Ringkasan & Statistik')

@section('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }

    .stat-card {
        background: var(--glass-bg);
        backdrop-filter: var(--glass-blur);
        -webkit-backdrop-filter: var(--glass-blur);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 1.75rem;
        box-shadow: 0 10px 30px rgba(59, 130, 246, 0.02);
        display: flex;
        align-items: center;
        gap: 1.25rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
        opacity: 0;
        transition: opacity 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(59, 130, 246, 0.08);
        border-color: rgba(59, 130, 246, 0.15);
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        color: white;
    }

    .stat-icon-revenue {
        background: linear-gradient(135deg, #10b981, #059669);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.25);
    }

    .stat-icon-orders {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.25);
    }

    .stat-icon-drivers {
        background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        box-shadow: 0 6px 20px rgba(139, 92, 246, 0.25);
    }

    .stat-icon-deliveries {
        background: linear-gradient(135deg, #ec4899, #be185d);
        box-shadow: 0 6px 20px rgba(236, 72, 153, 0.25);
    }

    .stat-details {
        display: flex;
        flex-direction: column;
    }

    .stat-val {
        font-family: 'Outfit', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
        margin-top: 0.15rem;
    }

    .stat-label {
        font-size: 0.85rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    /* Content Layout */
    .dashboard-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }

    @media (max-width: 992px) {
        .dashboard-layout {
            grid-template-columns: 1fr;
        }
    }

    .section-title {
        font-family: 'Outfit', sans-serif;
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        color: var(--accent-primary);
    }

    /* Tables */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        color: var(--text-primary);
        font-weight: 600;
        padding: 1rem;
        font-size: 0.85rem;
        text-transform: uppercase;
        border-bottom: 1px solid var(--border-color);
        background: rgba(0, 0, 0, 0.01);
    }

    td {
        padding: 1rem;
        font-size: 0.9rem;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }

    tr:hover td {
        background: rgba(59, 130, 246, 0.01);
        color: var(--text-primary);
    }

    .price-badge {
        font-family: monospace;
        font-weight: 700;
        color: #10b981;
    }

    .order-link {
        color: var(--accent-primary);
        text-decoration: none;
        font-weight: 600;
    }

    .order-link:hover {
        color: var(--accent-secondary);
    }

    /* Deliveries Today list */
    .delivery-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .delivery-item {
        background: rgba(255, 255, 255, 0.5);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.15rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        transition: all 0.3s;
    }

    .delivery-item:hover {
        border-color: rgba(59, 130, 246, 0.15);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.04);
        background: #ffffff;
    }

    .delivery-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .driver-name {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .driver-name i {
        color: var(--accent-primary);
    }

    .delivery-info {
        font-size: 0.85rem;
        color: var(--text-secondary);
        display: flex;
        justify-content: space-between;
    }

    /* Kalender Styles */
    .calendar-navigation {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .calendar-title {
        font-family: 'Outfit', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1px;
        background: var(--border-color);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        overflow: hidden;
    }

    .calendar-header-day {
        background: rgba(255, 255, 255, 0.9);
        color: var(--text-primary);
        font-weight: 700;
        text-align: center;
        padding: 1rem 0.5rem;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .calendar-day {
        background: rgba(255, 255, 255, 0.65);
        backdrop-filter: var(--glass-blur);
        min-height: 120px;
        padding: 0.75rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        transition: background-color 0.3s;
    }

    .calendar-day:hover {
        background: rgba(255, 255, 255, 0.85);
    }

    .calendar-day.empty {
        background: rgba(0, 0, 0, 0.02);
    }

    .calendar-day.today {
        background: rgba(59, 130, 246, 0.05);
        box-shadow: inset 0 0 0 2px rgba(59, 130, 246, 0.2);
    }

    .calendar-day.today .day-number {
        background: var(--accent-primary);
        color: white;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }

    .day-number {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-primary);
        align-self: flex-start;
    }

    .day-tasks {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        flex: 1;
        overflow-y: auto;
    }

    .task-pill {
        font-size: 0.725rem;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        border: 1px solid transparent;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .task-pill:hover {
        transform: scale(1.03);
    }

    .task-antar {
        background: rgba(59, 130, 246, 0.08);
        color: var(--accent-primary);
        border-color: rgba(59, 130, 246, 0.15);
    }

    .task-jemput {
        background: rgba(6, 182, 212, 0.08);
        color: var(--accent-secondary);
        border-color: rgba(6, 182, 212, 0.15);
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <h1>Dasbor Admin RME</h1>
        <p>Sistem Penyewaan dan Perentalan Alat Alat Event.</p>
    </div>
</div>

<!-- Stats Widgets Grid -->
<div class="stats-grid">
    <!-- Stat 1: Pendapatan -->
    <div class="stat-card">
        <div class="stat-icon stat-icon-revenue">
            <i class="fa-solid fa-money-bill-wave"></i>
        </div>
        <div class="stat-details">
            <span class="stat-label">Total Pendapatan</span>
            <span class="stat-val">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Stat 2: Total Pemesanan -->
    <div class="stat-card">
        <div class="stat-icon stat-icon-orders">
            <i class="fa-solid fa-file-invoice-dollar"></i>
        </div>
        <div class="stat-details">
            <span class="stat-label">Total Pemesanan</span>
            <span class="stat-val">{{ $totalOrders }} Order</span>
        </div>
    </div>

    <!-- Stat 3: Driver Aktif -->
    <div class="stat-card">
        <div class="stat-icon stat-icon-drivers">
            <i class="fa-solid fa-user-gear"></i>
        </div>
        <div class="stat-details">
            <span class="stat-label">Driver Aktif</span>
            <span class="stat-val">{{ $activeDriversCount }} Driver</span>
        </div>
    </div>

    <!-- Stat 4: Pengiriman Berjalan -->
    <div class="stat-card">
        <div class="stat-icon stat-icon-deliveries">
            <i class="fa-solid fa-truck-fast"></i>
        </div>
        <div class="stat-details">
            <span class="stat-label">Pengiriman Berjalan</span>
            <span class="stat-val">{{ $activeDeliveriesCount }} Tugas</span>
        </div>
    </div>
</div>

<!-- Main Panels Layout -->
<div class="dashboard-layout">
    <!-- Kiri: Recent Orders -->
    <div class="left-panel">
        <h2 class="section-title"><i class="fa-solid fa-history"></i> Pemesanan Terbaru</h2>
        <div class="glass-card" style="padding: 0;">
            <div style="overflow-x: auto; width: 100%;">
                @if($recentOrders->isEmpty())
                    <p style="color: var(--text-secondary); text-align: center; padding: 2rem 0;">
                        Belum ada pemesanan masuk.
                    </p>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Pelanggan</th>
                                <th>Tanggal Mulai</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th style="text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td><strong>#{{ $order->id_order }}</strong></td>
                                    <td>
                                        <div style="font-weight: 600; color: var(--text-primary);">{{ $order->pelanggan->nama }}</div>
                                        <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ $order->pelanggan->no_hp }}</div>
                                    </td>
                                    <td>{{ $order->tgl_mulai->format('d M Y') }}</td>
                                    <td><span class="price-badge">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span></td>
                                    <td>
                                        @php
                                            $statusLower = strtolower($order->status_sewa);
                                        @endphp
                                        @if($statusLower === 'pending')
                                            <span class="badge badge-pending">Pending</span>
                                        @elseif($statusLower === 'disetujui')
                                            <span class="badge badge-success" style="background: rgba(16, 185, 129, 0.08); border-color: rgba(16, 185, 129, 0.18); color: var(--success);">Disetujui</span>
                                        @elseif($statusLower === 'diproses')
                                            <span class="badge badge-process">Diproses</span>
                                        @elseif($statusLower === 'selesai')
                                            <span class="badge badge-success">Selesai</span>
                                        @else
                                            <span class="badge badge-pending">{{ $order->status_sewa }}</span>
                                        @endif
                                    </td>
                                    <td style="text-align: right;">
                                        <a href="{{ route('admin.order.show', $order->id_order) }}" class="btn btn-outline btn-sm" style="padding: 0.4rem 0.75rem; font-size: 0.8rem; border-radius: 8px;">
                                            <i class="fa-solid fa-search"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>

    <!-- Kanan: Today's Deliveries -->
    <div class="right-panel">
        <h2 class="section-title"><i class="fa-solid fa-truck"></i> Pengiriman Hari Ini</h2>
        <div class="delivery-list">
            @if($todayDeliveries->isEmpty())
                <div class="glass-card" style="text-align: center; color: var(--text-secondary); padding: 2rem 1rem;">
                    <i class="fa-solid fa-calendar-xmark" style="font-size: 2.5rem; color: rgba(59, 130, 246, 0.15); margin-bottom: 0.75rem;"></i>
                    <p style="font-size: 0.9rem;">Tidak ada jadwal pengiriman atau penjemputan untuk hari ini.</p>
                </div>
            @else
                @foreach($todayDeliveries as $delivery)
                    <div class="delivery-item">
                        <div class="delivery-header">
                            <span class="driver-name"><i class="fa-solid fa-user-gear"></i> {{ $delivery->driver->nama }}</span>
                            <span class="badge {{ $delivery->tipe_tugas === 'Antar' ? 'badge-process' : 'badge-pending' }}" style="padding: 0.15rem 0.5rem; font-size: 0.7rem;">
                                {{ $delivery->tipe_tugas }}
                            </span>
                        </div>
                        <div class="delivery-info">
                            <span>Order ID: <strong>#{{ $delivery->order->id_order }}</strong></span>
                            <span>
                                @if($delivery->status_tugas === 'pending')
                                    <span style="color: var(--warning); font-weight: 600;">Pending</span>
                                @elseif($delivery->status_tugas === 'proses')
                                    <span style="color: var(--accent-primary); font-weight: 600;">Proses</span>
                                @elseif($delivery->status_tugas === 'selesai')
                                    <span style="color: var(--success); font-weight: 600;">Selesai</span>
                                @else
                                    <span>{{ $delivery->status_tugas }}</span>
                                @endif
                            </span>
                        </div>
                        @if($delivery->catatan_kondisi_alat)
                            <div style="font-size: 0.75rem; color: var(--text-secondary); border-top: 1px dashed var(--border-color); padding-top: 0.5rem; margin-top: 0.25rem;">
                                <strong>Catatan:</strong> {{ $delivery->catatan_kondisi_alat }}
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<!-- Kalender Logistik & Event -->
@php
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
    $daysInMonth = date('t', $firstDayOfMonth);
    $dayOfWeek = date('w', $firstDayOfMonth);
    $monthNames = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    $prevMonth = $month - 1;
    $prevYear = $year;
    if ($prevMonth == 0) {
        $prevMonth = 12;
        $prevYear--;
    }

    $nextMonth = $month + 1;
    $nextYear = $year;
    if ($nextMonth == 13) {
        $nextMonth = 1;
        $nextYear++;
    }
@endphp
<div style="margin-top: 2rem;" id="calendar-section">
    <div class="calendar-navigation" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h2 class="section-title" style="margin-bottom: 0;"><i class="fa-solid fa-calendar-days"></i> Kalender Event & Logistik ({{ $monthNames[$month] }} {{ $year }})</h2>
        <div>
            <a href="{{ route('admin.dashboard', ['month' => $prevMonth, 'year' => $prevYear]) }}#calendar-section" class="btn btn-outline btn-sm"><i class="fa-solid fa-chevron-left"></i> Sebelumnya</a>
            <a href="{{ route('admin.dashboard', ['month' => $nextMonth, 'year' => $nextYear]) }}#calendar-section" class="btn btn-outline btn-sm">Berikutnya <i class="fa-solid fa-chevron-right"></i></a>
        </div>
    </div>
    <div class="glass-card" style="padding: 1.5rem;">
        <div class="calendar-grid">
            <div class="calendar-header-day" style="color: var(--danger);">Minggu</div>
            <div class="calendar-header-day">Senin</div>
            <div class="calendar-header-day">Selasa</div>
            <div class="calendar-header-day">Rabu</div>
            <div class="calendar-header-day">Kamis</div>
            <div class="calendar-header-day">Jumat</div>
            <div class="calendar-header-day">Sabtu</div>

            @for($i = 0; $i < $dayOfWeek; $i++)
                <div class="calendar-day empty"></div>
            @endfor

            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $currentDateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
                    $dayTasks = $groupedPengirimans->get($currentDateStr, collect());
                    $dayOrders = $groupedOrders[$currentDateStr] ?? [];
                    $isToday = $currentDateStr === date('Y-m-d');
                @endphp
                <div class="calendar-day {{ $isToday ? 'today' : '' }}">
                    <div class="day-number">{{ $day }}</div>
                    <div class="day-tasks">
                        @foreach($dayOrders as $order)
                            <a href="{{ route('admin.order.show', $order->id_order) }}" style="text-decoration: none;">
                                <div class="task-pill" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border-color: rgba(239, 68, 68, 0.2);" title="Pelanggan: {{ $order->nama_pelanggan }}&#10;Status: {{ $order->status_sewa }}">
                                    <i class="fa-solid fa-bookmark" style="margin-right:3px;"></i> <strong>#{{ $order->id_order }}</strong>
                                </div>
                            </a>
                        @endforeach
                        @foreach($dayTasks as $task)
                            <a href="{{ route('admin.order.show', $task->order->id_order) }}" style="text-decoration: none;">
                                <div class="task-pill {{ $task->tipe_tugas === 'Antar' ? 'task-antar' : 'task-jemput' }}" title="Driver: {{ $task->driver->nama }}&#10;Tugas: {{ $task->tipe_tugas }}">
                                    <strong>#{{ $task->order->id_order }}</strong> - {{ $task->tipe_tugas }}
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endfor

            @php
                $totalSlots = $dayOfWeek + $daysInMonth;
                $remainingSlots = 7 - ($totalSlots % 7);
            @endphp
            @if($remainingSlots < 7)
                @for($i = 0; $i < $remainingSlots; $i++)
                    <div class="calendar-day empty"></div>
                @endfor
            @endif
        </div>
    </div>
</div>

<!-- Direktori Pelanggan & Driver -->
<div class="dashboard-layout" style="margin-top: 2rem;">
    <div class="left-panel">
        <h2 class="section-title"><i class="fa-solid fa-users"></i> Direktori Pelanggan Terbaru</h2>
        <div class="glass-card" style="padding: 0;">
            <div style="overflow-x: auto; width: 100%;">
                @if($direktoriPelanggan->isEmpty())
                    <p style="color: var(--text-secondary); text-align: center; padding: 2rem 0;">Belum ada pelanggan terdaftar.</p>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Nama Pelanggan</th>
                                <th>No. Handphone</th>
                                <th>Bergabung</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($direktoriPelanggan as $plg)
                                <tr>
                                    <td><strong>{{ $plg->nama }}</strong></td>
                                    <td>{{ $plg->no_hp }}</td>
                                    <td>{{ $plg->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
    
    <div class="right-panel">
        <h2 class="section-title"><i class="fa-solid fa-id-card"></i> Manajemen Driver</h2>
        <div class="delivery-list">
            @if($manajemenDriver->isEmpty())
                <div class="glass-card" style="text-align: center; color: var(--text-secondary); padding: 2rem 1rem;">
                    <p style="font-size: 0.9rem;">Belum ada driver yang terdaftar.</p>
                </div>
            @else
                @foreach($manajemenDriver as $drv)
                    <div class="delivery-item" style="flex-direction: row; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <div class="user-avatar" style="width: 36px; height: 36px; font-size: 0.8rem;">
                                {{ strtoupper(substr($drv->nama, 0, 2)) }}
                            </div>
                            <div>
                                <div style="font-weight: 600; color: var(--text-primary);">{{ $drv->nama }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-secondary);">{{ $drv->no_hp }}</div>
                            </div>
                        </div>
                        <div>
                            @if($drv->status_aktif)
                                <span class="badge badge-success" style="font-size: 0.7rem;">Aktif</span>
                            @else
                                <span class="badge badge-pending" style="font-size: 0.7rem;">Nonaktif</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<!-- Katalog Alat -->
<div style="margin-top: 2rem;">
    <h2 class="section-title"><i class="fa-solid fa-boxes-stacked"></i> Katalog Alat & Layanan (Top 10)</h2>
    <div class="glass-card" style="padding: 0;">
        <div style="overflow-x: auto; width: 100%;">
            @if($katalogAlat->isEmpty())
                <p style="color: var(--text-secondary); text-align: center; padding: 2rem 0;">Belum ada data alat.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Nama Paket / Alat</th>
                            <th>Harga Sewa</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($katalogAlat as $alat)
                            <tr>
                                <td>
                                    <span style="font-size: 0.8rem; padding: 0.2rem 0.6rem; background: rgba(59, 130, 246, 0.1); color: var(--accent-primary); border-radius: 6px; font-weight: 600;">
                                        {{ strtoupper($alat->kategori) }}
                                    </span>
                                </td>
                                <td><strong>{{ $alat->nama_layanan }}</strong></td>
                                <td><span class="price-badge">Rp {{ number_format($alat->harga, 0, ',', '.') }}</span></td>
                                <td>
                                    @if($alat->is_available)
                                        <span style="color: var(--success); font-weight: 600; font-size: 0.85rem;"><i class="fa-solid fa-circle-check"></i> Tersedia</span>
                                    @else
                                        <span style="color: var(--danger); font-weight: 600; font-size: 0.85rem;"><i class="fa-solid fa-circle-xmark"></i> Kosong</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
