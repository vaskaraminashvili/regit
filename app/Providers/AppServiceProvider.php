<?php

namespace App\Providers;

use App\Services\BogPaymentService;
use App\Services\CartService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CartService::class);
        $this->app->singleton(BogPaymentService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['components.header', 'components.layouts.master'], function ($view) {
            $view->with('cartCount', app(CartService::class)->count());
        });
    }
}
