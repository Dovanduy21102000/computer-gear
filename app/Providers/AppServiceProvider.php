<?php

namespace App\Providers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Paginator::useBootstrapFive();

        View::composer('*', function ($view) {
            $total_items = 0;

            if (Auth::check()) {
                $total_items = CartItem::whereHas('cart', function ($query) {
                    $query->where('user_id', Auth::id());
                })->get()->sum(function ($item) {
                    // If product_variant_id contains multiple variants (e.g. "4 | 5")
                    if (strpos($item->product_variant_id, '|') !== false) {
                        return count(explode('|', $item->product_variant_id));
                    }
                    return 1;
                });
            }
            $topViewedProducts = Product::orderByDesc('views')
                ->get()
                ->shuffle()
                ->take(3);
            $activeProducts = Product::where('status', '1')
                ->get()
                ->shuffle()
                ->take(3);
            $topRatedProducts = Product::withCount('comments')
                ->withAvg('comments', 'rating') // Tính trung bình rating của sản phẩm
                ->orderByDesc('comments_avg_rating') // Sắp xếp theo rating trung bình
                ->take(3)
                ->get()
                ->shuffle(); // Trộn ngẫu nhiên
            $activeProducts = Product::where('status', '1') // hoặc status = 1 tuỳ bạn định nghĩa

            ->take(10) // Lấy top 10 sản phẩm nhiều lượt xem nhất (có thể điều chỉnh)
            ->get()
            ->shuffle() // Trộn ngẫu nhiên
            ->take(3);

            $view->with([
                'total_items' => $total_items,
                'topViewedProducts' => $topViewedProducts,
                'activeProducts' => $activeProducts,
                'topRatedProducts' =>$topRatedProducts,
                'activeProducts' =>$activeProducts
            ]);

            // $view->with('total_items', $total_items);
        });
    }
}
