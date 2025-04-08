<?php

namespace App\Providers;

use App\Models\CartItem;
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
                })->count();
            }

            $view->with('total_items', $total_items);
        });
    }
}
