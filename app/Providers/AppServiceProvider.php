<?php

namespace App\Providers;

use App\Service\Impl\InvoiceServiceImp;
use App\Service\InvoiceServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // invoice service
        $this->app->bind(InvoiceServiceInterface::class, InvoiceServiceImp::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
