<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\MediaCategory;
use App\Observers\MediaCategoryObserver;

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
    MediaCategory::observe(MediaCategoryObserver::class);
}
}
