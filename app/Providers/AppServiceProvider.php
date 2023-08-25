<?php

namespace App\Providers;

use Domain\Payment\Enums\IPGType;
use Domain\Payment\IPG\IPG;
use Domain\Payment\IPG\Paypal;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(IPG::class, function(){
            return config('ipg.default')->getIPG();
        });
    }
}
