<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Superadmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Tampilkan form login.
     */
    public function showLoginForm()
    {
        // Jika sudah login, langsung alihkan ke halaman utama
        if (session()->has('admin_id')) {
            return redirect()->route('admin.order.index');
        }

        return view('admin.auth.login');
    }

    /**
     * Proses autentikasi login admin / superadmin.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->username;
        $password = $request->password;

        // 1. Coba cari di tabel Admin
        $admin = Admin::where('username', $username)->first();
        if ($admin && Hash::check($password, $admin->password)) {
            session([
                'admin_id' => $admin->id_admin,
                'admin_username' => $admin->username,
                'admin_nama' => $admin->nama,
                'admin_role' => 'admin',
            ]);

            return redirect()->route('admin.order.index')
                ->with('success', 'Selamat datang kembali, ' . $admin->nama . '!');
        }

        // 2. Coba cari di tabel Superadmin
        $superadmin = Superadmin::where('username', $username)->first();
        if ($superadmin && Hash::check($password, $superadmin->password)) {
            session([
                'admin_id' => $superadmin->id_superadmin,
                'admin_username' => $superadmin->username,
                'admin_nama' => 'Super Administrator',
                'admin_role' => 'superadmin',
            ]);

            return redirect()->route('admin.order.index')
                ->with('success', 'Selamat datang kembali, Super Administrator!');
        }

        // Jika gagal
        return back()->withInput()->withErrors([
            'login_error' => 'Username atau password yang Anda masukkan salah.',
        ]);
    }

    /**
     * Hapus sesi & logout.
     */
    public function logout(Request $request)
    {
        $request->session()->forget(['admin_id', 'admin_username', 'admin_nama', 'admin_role']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
