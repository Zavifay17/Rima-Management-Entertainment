<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManageAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admins = Admin::all();
        return view('admin.manage_admin.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.manage_admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:admin,username',
            'password' => 'required|string|min:6|confirmed',
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
        ]);

        Admin::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'id_superadmin' => session('admin_id'), // Track which superadmin created this admin
        ]);

        return redirect()->route('admin.manage-admin.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $admin = Admin::findOrFail($id);
        return view('admin.manage_admin.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $admin = Admin::findOrFail($id);
        
        $rules = [
            'username' => 'required|string|max:255|unique:admin,username,' . $admin->id_admin . ',id_admin',
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
        ];

        // Jika password diisi, maka akan diupdate
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:6|confirmed';
        }

        $request->validate($rules);

        $admin->username = $request->username;
        $admin->nama = $request->nama;
        $admin->no_hp = $request->no_hp;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect()->route('admin.manage-admin.index')->with('success', 'Data admin berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $admin = Admin::findOrFail($id);
        $admin->delete();

        return redirect()->route('admin.manage-admin.index')->with('success', 'Admin berhasil dihapus.');
    }
}
