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

    public function index(Request $request)
    {
        $totalUsers = DB::table('users')->count();

        // Lấy thông tin lọc từ request
        $filterType = $request->get('filterType', 'month'); // Mặc định lọc theo tháng
        $startDate = null;
        $endDate = null;

        // Xử lý lọc theo ngày cụ thể nếu có
        if ($request->has('startDate') && $request->has('endDate')) {
            $startDate = Carbon::parse($request->startDate)->startOfDay();
            $endDate = Carbon::parse($request->endDate)->endOfDay();
            $filterType = 'custom';
        } else {
            // Xử lý lọc theo loại (ngày/tháng/năm)
            $now = Carbon::now();

            switch ($filterType) {
                case 'today':
                    $startDate = $now->copy()->startOfDay();
                    $endDate = $now->copy()->endOfDay();

                    // Khoảng thời gian trước đó để so sánh
                    $previousStartDate = $now->copy()->subDay()->startOfDay();
                    $previousEndDate = $now->copy()->subDay()->endOfDay();
                    break;

                case 'month':
                    $startDate = $now->copy()->startOfMonth();
                    $endDate = $now->copy()->endOfMonth();

                    // Khoảng thời gian trước đó để so sánh
                    $previousStartDate = $now->copy()->subMonth()->startOfMonth();
                    $previousEndDate = $now->copy()->subMonth()->endOfMonth();
                    break;

                case 'year':
                    $startDate = $now->copy()->startOfYear();
                    $endDate = $now->copy()->endOfYear();

                    // Khoảng thời gian trước đó để so sánh
                    $previousStartDate = $now->copy()->subYear()->startOfYear();
                    $previousEndDate = $now->copy()->subYear()->endOfYear();
                    break;

                default:
                    $startDate = $now->copy()->startOfMonth();
                    $endDate = $now->copy()->endOfMonth();

                    // Khoảng thời gian trước đó để so sánh
                    $previousStartDate = $now->copy()->subMonth()->startOfMonth();
                    $previousEndDate = $now->copy()->subMonth()->endOfMonth();
                    break;
            }
        }

        // Nếu là lọc tùy chỉnh, tự động tính khoảng thời gian trước đó để so sánh
        if ($filterType === 'custom') {
            $dateDiff = $endDate->diffInDays($startDate);
            $previousStartDate = $startDate->copy()->subDays($dateDiff + 1);
            $previousEndDate = $startDate->copy()->subDay();
        }

        // 1. Thống kê đơn hàng
        $orders = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $previousOrders = Order::whereBetween('created_at', [$previousStartDate, $previousEndDate])->count();

        // Tính phần trăm tăng trưởng đơn hàng
        $growthPercentageOrders = $previousOrders > 0
            ? (($orders - $previousOrders) / $previousOrders) * 100
            : ($orders > 0 ? 100 : 0);

        // 2. Thống kê doanh thu
        $revenue1 = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_price');

        $previousRevenue = Order::where('status', 'completed')
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->sum('total_price');

        // Tính phần trăm tăng trưởng doanh thu
        $percentageIncreaseRevenue = $previousRevenue > 0
            ? (($revenue1 - $previousRevenue) / $previousRevenue) * 100
            : ($revenue1 > 0 ? 100 : 0);

        // 3. Thống kê khách hàng
        $customers1 = User::whereBetween('created_at', [$startDate, $endDate])->count();
        $previousCustomers = User::whereBetween('created_at', [$previousStartDate, $previousEndDate])->count();

        // Tính phần trăm tăng trưởng khách hàng
        $percentageChangeCustomers = $previousCustomers > 0
            ? (($customers1 - $previousCustomers) / $previousCustomers) * 100
            : ($customers1 > 0 ? 100 : 0);

        // Định dạng hiển thị khoảng thời gian
        $filterDisplay = $this->getFilterDisplay($filterType, $startDate, $endDate);


        //
        $year = Carbon::now()->year; // Lấy năm hiện tại
        $months = range(1, 12);

        // Số đơn hàng theo tháng
        $salesRaw = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as total_orders')
            ->where('status', 'completed')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('total_orders', 'month');

        // Doanh thu theo tháng (chia đơn vị triệu)
        $revenueRaw = Order::selectRaw('MONTH(created_at) as month, SUM(total_price) as total_revenue')
            ->where('status', 'completed')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('total_revenue', 'month');

        // Số khách hàng mới theo tháng
        $customersRaw = User::selectRaw('MONTH(created_at) as month, COUNT(*) as total_customers')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->pluck('total_customers', 'month');

        // Chuẩn hóa dữ liệu thành mảng 12 phần tử cho biểu đồ
        $sales = [];
        $revenue = [];
        $customers = [];

        foreach ($months as $month) {
            $sales[] = $salesRaw[$month] ?? 0;
            $revenue[] = round(($revenueRaw[$month] ?? 0) / 1_000_000, 2); // Tính theo triệu
            $customers[] = $customersRaw[$month] ?? 0;
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
            'totalUsers',
            'orders',
            'growthPercentageOrders',
            'revenue1',
            'percentageIncreaseRevenue',
            'customers1',
            'percentageChangeCustomers',
            'filterType',
            'startDate',
            'endDate',
            'filterDisplay',
            //
            'sales',
            'revenue',
            'customers',
            'ordersLatest',
            'topSellingProducts',
            'recentActivities',
            'latestPosts'
        ));
    }
    private function getFilterDisplay($filterType, $startDate, $endDate)
    {
        switch ($filterType) {
            case 'today':
                return 'Hôm nay';
            case 'month':
                return 'Tháng này';
            case 'year':
                return 'Năm nay';
            case 'custom':
                return $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y');
            default:
                return 'Tháng này';
        }
    }
}