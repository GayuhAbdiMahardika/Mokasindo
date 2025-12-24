<?php

namespace App\Providers;

use App\Services\AuctionStatusService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
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
        // Sync auction status without relying on cron. Throttled to once per minute.
        try {
            Cache::remember('auction_status_last_sync', 60, function () {
                App::make(AuctionStatusService::class)->sync();
                return now();
            });
        } catch (\Exception $e) {
            // Log error but don't crash the app
            \Illuminate\Support\Facades\Log::warning('Auction sync failed: ' . $e->getMessage());
        }

        View::share('availableLocales', ['id' => 'ID', 'en' => 'EN']);
        View::share('currentLocale', App::getLocale());
    }
}
