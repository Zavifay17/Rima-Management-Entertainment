@extends('layouts.admin')

@section('title', 'Tambah Admin')

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
        <a href="{{ route('admin.manage-admin.index') }}" style="color: var(--text-secondary); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; margin-bottom: 0.75rem; transition: color 0.3s;" onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-secondary)'">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
        </a>
        <h1>Tambah Admin</h1>
        <p>Menambahkan data administrator baru</p>
    </div>
</div>

<div class="form-container">
    <div class="glass-card">
        <form action="{{ route('admin.manage-admin.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required placeholder="Masukkan nama lengkap">
                @error('nama')
                    <span class="validation-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="no_hp">No HP</label>
                <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" required placeholder="Contoh: 08123456789">
                @error('no_hp')
                    <span class="validation-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" required placeholder="Masukkan username">
                @error('username')
                    <span class="validation-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Minimal 6 karakter">
                @error('password')
                    <span class="validation-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="Ketik ulang password">
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.manage-admin.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Admin</button>
            </div>
        </form>
    </div>
</div>
@endsection
