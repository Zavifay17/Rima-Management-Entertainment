@extends('layouts.admin')

@section('title', 'Kalender Pengiriman')

@section('styles')
<style>
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
@php
    // Day calculation
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
    $daysInMonth = date('t', $firstDayOfMonth);
    $dayOfWeek = date('w', $firstDayOfMonth);

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

    $monthNames = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
@endphp

<div class="page-header">
    <div class="page-title">
        <h1>Kalender Event - Pengantaran dan Penjemputan</h1>
        <p>Pantau Kalender event yang di pesan oleh pelanggan dan Kalender pengantaran dan penjemputan driver.</p>
    </div>
</div>

<div class="glass-card" style="padding: 1.5rem;">
    <!-- Calendar Navigation Header -->
    <div class="calendar-navigation">
        <a href="{{ route('admin.pengiriman.calendar', ['month' => $prevMonth, 'year' => $prevYear]) }}" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-chevron-left"></i> Bulan Sebelumnya
        </a>
        
        <span class="calendar-title">
            <i class="fa-regular fa-calendar-days"></i>
            {{ $monthNames[$month] }} {{ $year }}
        </span>

        <a href="{{ route('admin.pengiriman.calendar', ['month' => $nextMonth, 'year' => $nextYear]) }}" class="btn btn-outline btn-sm">
            Bulan Berikutnya <i class="fa-solid fa-chevron-right"></i>
        </a>
    </div>

    <!-- Calendar Grid -->
    <div class="calendar-grid">
        <!-- Weekday Headers -->
        <div class="calendar-header-day" style="color: var(--danger);">Minggu</div>
        <div class="calendar-header-day">Senin</div>
        <div class="calendar-header-day">Selasa</div>
        <div class="calendar-header-day">Rabu</div>
        <div class="calendar-header-day">Kamis</div>
        <div class="calendar-header-day">Jumat</div>
        <div class="calendar-header-day">Sabtu</div>

        <!-- Empty Slots before Month Start -->
        @for($i = 0; $i < $dayOfWeek; $i++)
            <div class="calendar-day empty"></div>
        @endfor

        <!-- Days of the Month -->
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
                                <i class="fa-solid fa-bookmark" style="margin-right:3px;"></i> <strong>#{{ $order->id_order }}</strong> - BOOKED
                            </div>
                        </a>
                    @endforeach
                    @foreach($dayTasks as $task)
                        <a href="{{ route('admin.order.show', $task->order->id_order) }}" style="text-decoration: none;">
                            <div class="task-pill {{ $task->tipe_tugas === 'Antar' ? 'task-antar' : 'task-jemput' }}" title="Driver: {{ $task->driver->nama }}&#10;Tugas: {{ $task->tipe_tugas }}&#10;Catatan: {{ $task->catatan_kondisi_alat ?? '-' }}">
                                <strong>#{{ $task->order->id_order }}</strong> - {{ $task->driver->nama }}
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endfor

        <!-- Empty Slots to pad out the last week if necessary -->
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
@endsection
