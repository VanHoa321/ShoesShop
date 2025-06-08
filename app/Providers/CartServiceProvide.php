<?php

namespace App\Providers;

use App\Http\Controllers\Frontend\CartController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class CartServiceProvide extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer(['layout.web_layout'], function ($view) {
            $cartController = new CartController();
            $totalItemsInCart = $cartController->getCartItemCount();
            $view->with('totalItemsInCart', $totalItemsInCart);
        });
    }
}
