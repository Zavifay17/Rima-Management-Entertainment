@extends('layouts.admin')

@section('title', 'Edit Driver Logistik')

@section('styles')
<style>
    .form-container {
        max-width: 600px;
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

    .help-text {
        color: var(--text-secondary);
        font-size: 0.8rem;
        margin-top: 0.5rem;
        display: block;
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="page-title">
        <a href="{{ route('admin.driver.index') }}" style="color: var(--text-secondary); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; margin-bottom: 0.75rem; transition: color 0.3s;" onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-secondary)'">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
        </a>
        <h1>Edit Driver Logistik</h1>
        <p>Ubah detail informasi driver logistik #{{ $driver->id_driver }} ({{ $driver->nama }}).</p>
    </div>
</div>

<div class="form-container">
    <div class="glass-card">
        <form action="{{ route('admin.driver.update', $driver->id_driver) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nama Lengkap -->
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $driver->nama) }}" placeholder="Contoh: Ahmad Subardjo" required>
                @error('nama')
                    <span class="validation-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <!-- Username (untuk login) -->
            <div class="form-group">
                <label for="username">Username (untuk Login Aplikasi)</label>
                <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $driver->username) }}" placeholder="Contoh: driver_ahmad" required>
                @error('username')
                    <span class="validation-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password Baru</label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Kosongkan jika tidak ingin mengubah password">
                <span class="help-text"><i class="fa-solid fa-circle-info"></i> Kosongkan kolom ini jika password driver tidak ingin diganti.</span>
                @error('password')
                    <span class="validation-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <!-- Nomor HP -->
            <div class="form-group">
                <label for="no_hp">Nomor Handphone (WhatsApp)</label>
                <input type="text" name="no_hp" id="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp', $driver->no_hp) }}" placeholder="Contoh: 081234567890" required>
                @error('no_hp')
                    <span class="validation-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <!-- Status Aktif -->
            <div class="form-group">
                <label for="status_aktif">Status Driver</label>
                <select name="status_aktif" id="status_aktif" class="form-control @error('status_aktif') is-invalid @enderror" required>
                    <option value="1" {{ old('status_aktif', $driver->status_aktif ? '1' : '0') == '1' ? 'selected' : '' }}>Aktif (Bisa Menerima Tugas)</option>
                    <option value="0" {{ old('status_aktif', $driver->status_aktif ? '1' : '0') == '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status_aktif')
                    <span class="validation-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="form-actions">
                <a href="{{ route('admin.driver.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
