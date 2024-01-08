<?php

namespace App\Providers;

use App\Service\ImageGenServiceInteface;
use App\Service\Impl\ImageGenServiceImpl;
use App\Service\Impl\InvoiceServiceImp;
use App\Service\Impl\PdfServiceImpl;
use App\Service\InvoiceServiceInterface;
use App\Service\PdfServiceInterface;
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
        $this->app->bind(PdfServiceInterface::class, PdfServiceImpl::class);
        $this->app->bind(ImageGenServiceInteface::class, ImageGenServiceImpl::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
