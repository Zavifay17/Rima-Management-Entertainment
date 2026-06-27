@extends('layouts.admin')

@section('title', 'Alokasikan Tugas Driver')

@section('styles')
<style>
    .form-container {
        max-width: 700px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 1.75rem;
    }

    .form-group label {
        display: block;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
    }

    .form-control {
        width: 100%;
        background: rgba(0, 0, 0, 0.02);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 0.85rem 1.2rem;
        color: var(--text-primary);
        font-size: 0.95rem;
        transition: all 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--accent-primary);
        background: rgba(0, 0, 0, 0.04);
        box-shadow: 0 0 12px rgba(99, 102, 241, 0.15);
    }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml;utf8,<svg fill='%23475569' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/><path d='M0 0h24v24H0z' fill='none'/></svg>");
        background-repeat: no-repeat;
        background-position: right 15px center;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 120px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2.5rem;
        border-top: 1px solid var(--border-color);
        padding-top: 1.5rem;
    }

    .validation-error {
        color: var(--danger);
        font-size: 0.825rem;
        margin-top: 0.5rem;
        display: block;
        font-weight: 500;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <a href="{{ route('admin.pengiriman.index') }}" style="color: var(--text-secondary); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; margin-bottom: 0.75rem; transition: color 0.3s;" onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-secondary)'">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
        </a>
        <h1>Alokasikan Tugas Driver</h1>
        <p>Buat tugas baru untuk ditugaskan kepada Driver (Antar atau Jemput barang/alat).</p>
    </div>
</div>

<div class="form-container">
    <div class="glass-card">
        <form action="{{ route('admin.pengiriman.store') }}" method="POST">
            @csrf

            <!-- Pilih Order / Barang -->
            <div class="form-group">
                <label for="id_order">Pilih Order Sewa</label>
                <select name="id_order" id="id_order" class="form-control @error('id_order') is-invalid @enderror">
                    <option value="" disabled selected>-- Pilih Order (Pelanggan) --</option>
                    @foreach($orders as $order)
                        <option value="{{ $order->id_order }}" {{ old('id_order', request('id_order')) == $order->id_order ? 'selected' : '' }}>
                            Order #{{ $order->id_order }} - {{ $order->pelanggan->nama }} (Mulai: {{ $order->tgl_mulai->format('d M Y') }})
                        </option>
                    @endforeach
                </select>
                @error('id_order')
                    <span class="validation-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <!-- Pilih Driver -->
            <div class="form-group">
                <label for="id_driver">Pilih Driver Logistik</label>
                <select name="id_driver" id="id_driver" class="form-control @error('id_driver') is-invalid @enderror">
                    <option value="" disabled selected>-- Pilih Driver Penerima Tugas --</option>
                    @foreach($drivers as $driver)
                        <option value="{{ $driver->id_driver }}" {{ old('id_driver') == $driver->id_driver ? 'selected' : '' }}>
                            {{ $driver->nama }} (HP: {{ $driver->no_hp }})
                        </option>
                    @endforeach
                </select>
                @error('id_driver')
                    <span class="validation-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <!-- Tipe Tugas -->
            <div class="form-group">
                <label for="tipe_tugas">Tipe Tugas</label>
                <select name="tipe_tugas" id="tipe_tugas" class="form-control @error('tipe_tugas') is-invalid @enderror">
                    <option value="" disabled selected>-- Pilih Tipe Tugas --</option>
                    <option value="Antar" {{ old('tipe_tugas') == 'Antar' ? 'selected' : '' }}>Antar Barang / Pasang Alat</option>
                    <option value="Jemput" {{ old('tipe_tugas') == 'Jemput' ? 'selected' : '' }}>Jemput Barang / Tarik Alat</option>
                </select>
                @error('tipe_tugas')
                    <span class="validation-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <!-- Tanggal Jadwal -->
            <div class="form-group">
                <label for="tgl_jadwal">Tanggal Rencana Pelaksanaan</label>
                <input type="date" name="tgl_jadwal" id="tgl_jadwal" class="form-control @error('tgl_jadwal') is-invalid @enderror" value="{{ old('tgl_jadwal', date('Y-m-d')) }}">
                @error('tgl_jadwal')
                    <span class="validation-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <!-- Catatan Khusus -->
            <div class="form-group">
                <label for="catatan_kondisi_alat">Catatan Kondisi Alat / Instruksi Driver</label>
                <textarea name="catatan_kondisi_alat" id="catatan_kondisi_alat" class="form-control @error('catatan_kondisi_alat') is-invalid @enderror" placeholder="Tulis instruksi khusus pengantaran, kelengkapan alat, atau kondisi barang saat diserahkan...">{{ old('catatan_kondisi_alat') }}</textarea>
                @error('catatan_kondisi_alat')
                    <span class="validation-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="form-actions">
                <a href="{{ route('admin.pengiriman.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Tugas ke Driver
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
