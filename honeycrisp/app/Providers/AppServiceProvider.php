<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Facility;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

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
        // check if the table exists first
        if (!Schema::hasTable('facilities')) {
            return;
        }
        $facilities = Facility::all()->where('status', 'active');
        View::share('facilities', $facilities);
    }
}
