<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    /**
     * Display a listing of completed orders and total income.
     */
    public function index()
    {
        // Get all completed orders
        $completedOrders = Order::whereIn('status_sewa', ['Selesai', 'selesai'])
            ->orderBy('updated_at', 'desc')
            ->get();

        // Calculate total income
        $totalIncome = $completedOrders->sum('total_harga');

        // Optional: you can group by month if needed in the view
        
        return view('admin.finance.index', compact('completedOrders', 'totalIncome'));
    }
}
