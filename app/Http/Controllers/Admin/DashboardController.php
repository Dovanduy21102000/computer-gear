<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct() {}

    public function index()
    {
        $totalUsers = DB::table('users')->count();

        $filter = request()->get('filter', 'month');
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Đếm số lượng đơn hàng theo filter
        switch ($filter) {
            case 'month':
                // Đếm đơn hàng trong tháng này
                $orders = Order::whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->count();

                // Đếm số lượng đơn hàng tháng trước
                $lastMonth = Carbon::now()->subMonth()->month;
                $lastMonthOrders = Order::whereMonth('created_at', $lastMonth)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->count();

                // Tính phần trăm tăng trưởng so với tháng trước
                $growthPercentageOrders = $lastMonthOrders > 0
                    ? (($orders - $lastMonthOrders) / $lastMonthOrders) * 100
                    : ($orders > 0 ? 100 : 0);
                break;

            case 'year':
                // Đếm đơn hàng trong năm nay
                $orders = Order::whereYear('created_at', Carbon::now()->year)
                    ->count();

                // Đếm số lượng đơn hàng năm trước
                $lastYear = Carbon::now()->subYear()->year;
                $lastYearOrders = Order::whereYear('created_at', $lastYear)
                    ->count();

                // Tính phần trăm tăng trưởng so với năm trước
                $growthPercentageOrders = $lastYearOrders > 0
                    ? (($orders - $lastYearOrders) / $lastYearOrders) * 100
                    : ($orders > 0 ? 100 : 0);
                break;

            case 'today':
            default:
                // Đếm đơn hàng hôm nay
                $orders = Order::whereDate('created_at', $today)
                    ->count();

                // Đếm số lượng đơn hàng hôm qua
                $yesterdayOrders = Order::whereDate('created_at', $yesterday)->count();

                // Tính phần trăm tăng trưởng so với hôm qua
                $growthPercentageOrders = $yesterdayOrders > 0
                    ? (($orders - $yesterdayOrders) / $yesterdayOrders) * 100
                    : ($orders > 0 ? 100 : 0);
                break;
        }

        // Doanh thu tháng này
        $currentMonth = Carbon::now()->startOfMonth();
        $revenueThisMonth = Order::where('status', 'completed')->whereBetween('created_at', [$currentMonth, Carbon::now()])
            ->sum('total_price');

        // Doanh thu tháng trước
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endLastMonth = Carbon::now()->subMonth()->endOfMonth();
        $revenueLastMonth = Order::where('status', 'completed')->whereBetween('created_at', [$lastMonth, $endLastMonth])
            ->sum('total_price');

        // Tính phần trăm thay đổi
        $percentageIncrease = $revenueLastMonth > 0
            ? (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100
            : 100; // Nếu tháng trước không có đơn hàng, mặc định tăng 100%

        // Lấy số lượng khách hàng mới trong năm nay
        $startOfYear = Carbon::now()->startOfYear();
        $customersThisYear = User::where('created_at', '>=', $startOfYear)->count();

        // Lấy số lượng khách hàng mới trong năm trước
        $startOfLastYear = Carbon::now()->subYear()->startOfYear();
        $endOfLastYear = Carbon::now()->subYear()->endOfYear();
        $customersLastYear = User::whereBetween('created_at', [$startOfLastYear, $endOfLastYear])->count();

        // Tính phần trăm thay đổi so với năm trước
        $percentageChange = $customersLastYear > 0
            ? (($customersThisYear - $customersLastYear) / $customersLastYear) * 100
            : 100; // Nếu năm trước không có khách hàng, mặc định tăng 100%


        $year = Carbon::now()->year; // Lấy năm hiện tại

        // Lấy dữ liệu số đơn hàng theo từng tháng
        $salesData = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as total_orders')
            ->where('status', 'completed')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total_orders', 'month');

        // Lấy doanh thu theo từng tháng
        $revenueData = Order::selectRaw('MONTH(created_at) as month, SUM(total_price) as total_revenue')
            ->where('status', 'completed')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total_revenue', 'month');

        // Lấy số khách hàng mới theo từng tháng
        $customerData = User::selectRaw('MONTH(created_at) as month, COUNT(*) as total_customers')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total_customers', 'month');

        // Chuẩn hóa dữ liệu cho ApexCharts (12 tháng)
        $months = range(1, 12);
        $sales = [];
        $revenue = [];
        $customers = [];

        foreach ($months as $month) {
            $sales[] = $salesData[$month] ?? 0;
            $revenue[] = $revenueData[$month] ?? 0;
            $customers[] = $customerData[$month] ?? 0;
        }
        // Lấy danh sách đơn hàng mới
        $ordersLatest = Order::with(['user', 'items.productVariant'])  // Đảm bảo tải các quan hệ với 'user' và 'items.productVariant'
            ->latest()  // Sắp xếp theo created_at giảm dần (mới nhất ở trên cùng)
            ->take(10)  // Lấy 10 đơn hàng mới nhất
            ->get();
        // dd($ordersToday);

        $topSellingProducts = Product::select('products.*')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereMonth('orders.created_at', Carbon::now()->month)
            ->whereYear('orders.created_at', Carbon::now()->year)
            ->selectRaw('SUM(order_items.quantity) as total_sold, SUM(order_items.quantity * order_items.price) as total_revenue')
            ->groupBy('products.id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();


        // Lấy danh sách người mua mới nhất
        $latestBuyers = Order::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'type' => 'order',
                    'name' => $order->user->name ?? 'Khách hàng',
                    'created_at' => $order->created_at,
                ];
            });

        // Lấy danh sách người dùng đăng ký mới nhất
        $latestUsers = User::latest()
            ->take(5)
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'user',
                    'name' => $user->name,
                    'created_at' => $user->created_at,
                ];
            });

        // Gộp hai danh sách lại
        $recentActivities = $latestBuyers->merge($latestUsers);

        // Sắp xếp theo thời gian mới nhất
        $recentActivities = $recentActivities->sortByDesc('created_at');

        $latestPosts = Post::latest()->take(5)->get();

        $usersByRole = DB::table('users')
            ->select('role', DB::raw('count(*) as count'))
            ->groupBy('role')
            ->get();

        $totalOrders = DB::table('orders')->count();

        $totalRevenue = DB::table('orders')->sum('total_price');

        $totalProducts = DB::table('products')->count();


        $template = 'backend.dashboard.home.index';

        return view('backend.dashboard.layout', compact(
            'template',
            'filter',
            'orders',
            'growthPercentageOrders',
            'revenueThisMonth',
            'percentageIncrease',
            'customersThisYear',
            'percentageChange',
            'sales',
            'revenue',
            'customers',
            'ordersLatest',
            'topSellingProducts',
            'recentActivities',
            'latestPosts'
        ));
    }
}
