<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    /**
     * Tampilkan daftar driver.
     */
    public function index()
    {
        $drivers = Driver::withCount('pengirimans')->orderBy('nama', 'asc')->get();
        return view('admin.driver.index', compact('drivers'));
    }

    /**
     * Tampilkan form tambah driver.
     */
    public function create()
    {
        return view('admin.driver.create');
    }

    /**
     * Simpan driver baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:driver,username',
            'password' => 'required|string|min:6',
            'no_hp' => 'required|string|max:20',
            'status_aktif' => 'required|boolean',
        ], [
            'username.unique' => 'Username driver ini sudah terdaftar.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        Driver::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'status_aktif' => (bool)$request->status_aktif,
        ]);

        return redirect()->route('admin.driver.index')
            ->with('success', 'Driver baru berhasil didaftarkan!');
    }

    /**
     * Tampilkan form edit driver.
     */
    public function edit($id_driver)
    {
        $driver = Driver::findOrFail($id_driver);
        return view('admin.driver.edit', compact('driver'));
    }

    /**
     * Perbarui data driver.
     */
    public function update(Request $request, $id_driver)
    {
        $driver = Driver::findOrFail($id_driver);

        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:driver,username,' . $driver->id_driver . ',id_driver',
            'password' => 'nullable|string|min:6',
            'no_hp' => 'required|string|max:20',
            'status_aktif' => 'required|boolean',
        ], [
            'username.unique' => 'Username driver ini sudah terdaftar.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        $updateData = [
            'nama' => $request->nama,
            'username' => $request->username,
            'no_hp' => $request->no_hp,
            'status_aktif' => (bool)$request->status_aktif,
        ];

        // Hanya update password jika diisi
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $driver->update($updateData);

        return redirect()->route('admin.driver.index')
            ->with('success', 'Data driver berhasil diperbarui!');
    }

    /**
     * Hapus driver.
     */
    public function destroy($id_driver)
    {
        $driver = Driver::findOrFail($id_driver);
        $driver->delete();

        return redirect()->route('admin.driver.index')
            ->with('success', 'Driver berhasil dihapus dari sistem!');
    }

    /**
     * Ubah status aktif/nonaktif driver secara langsung.
     */
    public function toggleStatus($id_driver)
    {
        $driver = Driver::findOrFail($id_driver);
        $driver->status_aktif = !$driver->status_aktif;
        $driver->save();

        $statusStr = $driver->status_aktif ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.driver.index')
            ->with('success', "Driver {$driver->nama} berhasil {$statusStr}!");
    }
}
