<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LayananSewa;
use App\Models\Superadmin;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    /**
     * Tampilkan katalog layanan sewa.
     */
    public function index()
    {
        $layanans = LayananSewa::orderBy('kategori', 'asc')->orderBy('nama_layanan', 'asc')->get();
        return view('admin.layanan.index', compact('layanans'));
    }

    /**
     * Tampilkan form tambah layanan.
     */
    public function create()
    {
        return view('admin.layanan.create');
    }

    /**
     * Simpan layanan baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'is_paket' => 'required|boolean',
        ]);

        $superadmin = Superadmin::first();

        LayananSewa::create([
            'nama_layanan' => $request->nama_layanan,
            'kategori' => $request->kategori,
            'satuan' => $request->satuan,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
            'is_paket' => (bool)$request->is_paket,
            'id_superadmin' => $superadmin ? $superadmin->id_superadmin : 1,
        ]);

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Alat / Paket sewa baru berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit layanan.
     */
    public function edit($id)
    {
        $layanan = LayananSewa::findOrFail($id);
        return view('admin.layanan.edit', compact('layanan'));
    }

    /**
     * Perbarui data layanan.
     */
    public function update(Request $request, $id)
    {
        $layanan = LayananSewa::findOrFail($id);

        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'is_paket' => 'required|boolean',
        ]);

        $layanan->update([
            'nama_layanan' => $request->nama_layanan,
            'kategori' => $request->kategori,
            'satuan' => $request->satuan,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
            'is_paket' => (bool)$request->is_paket,
        ]);

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Data katalog sewa berhasil diperbarui!');
    }

    /**
     * Hapus layanan dari katalog.
     */
    public function destroy($id)
    {
        $layanan = LayananSewa::findOrFail($id);
        $layanan->delete();

        return redirect()->route('admin.layanan.index')
            ->with('success', 'Alat / Paket sewa berhasil dihapus!');
    }
}
