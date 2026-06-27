@extends('layouts.admin')

@section('title', 'Edit Katalog Alat/Paket')

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
        background: rgba(255, 255, 255, 0.6);
        border: 1px solid rgba(59, 130, 246, 0.15);
        border-radius: 12px;
        padding: 0.85rem 1.2rem;
        color: var(--text-primary);
        font-size: 0.95rem;
        transition: all 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--accent-primary);
        background: #ffffff;
        box-shadow: 0 0 12px rgba(59, 130, 246, 0.15);
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
        <a href="{{ route('admin.layanan.index') }}" style="color: var(--text-secondary); text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; margin-bottom: 0.75rem; transition: color 0.3s;" onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-secondary)'">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Katalog
        </a>
        <h1>Edit Katalog Alat / Paket</h1>
        <p>Perbarui rincian spesifikasi atau tarif sewa barang sewa.</p>
    </div>
</div>

<div class="form-container">
    <div class="glass-card">
        <form action="{{ route('admin.layanan.update', $layanan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nama Layanan / Alat -->
            <div class="form-group">
                <label for="nama_layanan">Nama Alat / Layanan</label>
                <input type="text" name="nama_layanan" id="nama_layanan" class="form-control @error('nama_layanan') is-invalid @enderror" placeholder="Contoh: Genset Silent 100 kVA, Sound System 5000W" value="{{ old('nama_layanan', $layanan->nama_layanan) }}" required>
                @error('nama_layanan')
                    <span class="validation-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <!-- Kategori -->
            <div class="form-group">
                <label for="kategori">Kategori Alat</label>
                <input type="text" name="kategori" id="kategori" class="form-control @error('kategori') is-invalid @enderror" placeholder="Contoh: Kelistrikan, Sound System, Panggung, Lighting" value="{{ old('kategori', $layanan->kategori) }}" required>
                @error('kategori')
                    <span class="validation-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <!-- Satuan -->
                <div class="form-group">
                    <label for="satuan">Satuan</label>
                    <input type="text" name="satuan" id="satuan" class="form-control @error('satuan') is-invalid @enderror" placeholder="Contoh: Unit, Pcs, Paket" value="{{ old('satuan', $layanan->satuan) }}" required>
                    @error('satuan')
                        <span class="validation-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>

                <!-- Tipe (Paket / Satuan) -->
                <div class="form-group">
                    <label for="is_paket">Tipe Item</label>
                    <select name="is_paket" id="is_paket" class="form-control @error('is_paket') is-invalid @enderror" required>
                        <option value="0" {{ old('is_paket', $layanan->is_paket) == false ? 'selected' : '' }}>Satuan / Barang</option>
                        <option value="1" {{ old('is_paket', $layanan->is_paket) == true ? 'selected' : '' }}>Paket Layanan</option>
                    </select>
                    @error('is_paket')
                        <span class="validation-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Harga Sewa -->
            <div class="form-group">
                <label for="harga">Harga Sewa (Rupiah per Hari)</label>
                <input type="number" name="harga" id="harga" class="form-control @error('harga') is-invalid @enderror" placeholder="Contoh: 150000" value="{{ old('harga', (int)$layanan->harga) }}" min="0" required>
                @error('harga')
                    <span class="validation-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div class="form-group">
                <label for="deskripsi">Deskripsi Detail</label>
                <textarea name="deskripsi" id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" placeholder="Tulis rincian spesifikasi alat medis/kelengkapan paket sewa secara detail...">{{ old('deskripsi', $layanan->deskripsi) }}</textarea>
                @error('deskripsi')
                    <span class="validation-error"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="form-actions">
                <a href="{{ route('admin.layanan.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
