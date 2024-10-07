<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Facility;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Money\Money;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;

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

        Blade::directive('currentFiscalYear', function () {
            $currentMonth = date('m');
            $currentYear = date('Y');

            if ($currentMonth < 7) {
                return "<?php echo ($currentYear - 1);?>";
            }

            return "<?php echo $currentYear;?>";
        });

        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('update-category', function ($user, $category) {
            return $user->role === 'admin';
        });

        Gate::define('update-facility', function ($user, $facility) {
            return $user->role === 'admin';
        });



        


        // blade directive to get the start date of the current fiscal year
        Blade::directive('fiscalYearStart', function () {
            $currentMonth = date('m');
            $currentYear = date('Y');

            if ($currentMonth < 7) {
                return "<?php echo ($currentYear - 1) . '-07-01';?>";
            }

            return "<?php echo $currentYear . '-07-01';?>";
        });

        // blade directive to get the end date of the current fiscal year
        Blade::directive('fiscalYearEnd', function () {
            $currentMonth = date('m');
            $currentYear = date('Y');

            if ($currentMonth < 7) {
                return "<?php echo $currentYear . '-06-30';?>";
            }

            return "<?php echo ($currentYear + 1) . '-06-30';?>";
        });

        //

        // use bootstrap5 pagination
        \Illuminate\Pagination\Paginator::useBootstrap();
        
    }
}
