<?php

namespace App\Providers;

use App\Services\Discount\Buy5Get1FreeService;
use App\Services\Discount\DiscountService;
use App\Services\Discount\TenPercentDiscountService;
use App\Services\Discount\TwentyPercentDiscountService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //https://stackoverflow.com/questions/19365138/how-to-inject-multiple-classes-that-share-the-same-interface-in-laravel-4
        
        app()->bind(DiscountService::class, function ($app) {
            return new DiscountService([
                $app->make(TenPercentDiscountService::class),
                $app->make(TwentyPercentDiscountService::class),
                $app->make(Buy5Get1FreeService::class),
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
