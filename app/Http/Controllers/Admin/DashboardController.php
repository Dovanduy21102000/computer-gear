<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct() {}

    public function index()
    {
        $totalUsers = DB::table('users')->count();

        $usersByRole = DB::table('users')
            ->select('role', DB::raw('count(*) as count'))
            ->groupBy('role')
            ->get();

        $totalOrders = DB::table('orders')->count();

        $totalRevenue = DB::table('orders')->sum('total_price');

        $totalProducts = DB::table('products')->count();
        
      
        $template = 'backend.dashboard.home.index';
        return view('backend.dashboard.layout', compact('template', 'totalUsers', 'usersByRole', 'totalOrders', 'totalRevenue', 'totalProducts'));
    }
            
}
