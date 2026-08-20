<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Laporan Pemesanan & Lokasi
     */
    public function orders()
    {
        // Get all orders ordered by newest first
        $orders = Order::with(['orderDetails.layananSewa'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.report.orders', compact('orders'));
    }
}
