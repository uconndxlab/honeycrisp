<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Facility;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Money\Money;
use Illuminate\Support\Facades\Blade;

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

        // blade directive to convert cents to dollars and cents
        Blade::directive('dollars', function ($expression) {
            return "<?php echo number_format($expression / 100, 2);?>";
        });

        // use bootstrap5 pagination
        \Illuminate\Pagination\Paginator::useBootstrap();
        
    }
}
