<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    /**
     * Tampilkan direktori pelanggan.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('orders')
            ->select(
                'nama_pelanggan as nama',
                'no_hp_pelanggan as no_hp',
                'email_pelanggan as email',
                DB::raw('count(id_order) as total_orders'),
                DB::raw('sum(total_harga) as total_spent')
            )
            ->groupBy('nama_pelanggan', 'no_hp_pelanggan', 'email_pelanggan')
            ->orderBy('nama_pelanggan', 'asc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_pelanggan', 'ilike', '%' . $search . '%')
                  ->orWhere('no_hp_pelanggan', 'like', '%' . $search . '%')
                  ->orWhere('email_pelanggan', 'ilike', '%' . $search . '%');
            });
        }

        $customers = $query->get();

        return view('admin.pelanggan.index', compact('customers', 'search'));
    }
}
