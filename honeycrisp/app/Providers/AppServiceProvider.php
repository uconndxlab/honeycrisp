<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Facility;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Money\Money;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


use Livewire\Livewire;

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

        Livewire::component('user-search', \App\Livewire\UserSearch::class);
        Livewire::component('account-search', \App\Livewire\AccountSearch::class);

        // check if the table exists first
        if (!Schema::hasTable('facilities')) {
            return;
        }
        $facilities = Facility::where('status', 'active')->get();
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
            // if the user is an admin or the facility belongs to the user
            return $user->role === 'admin' || $facility->director_id === $user->id;
        });

        Gate::define('update-payment-account', function ($user, $paymentAccount) {
            return $user->role === 'admin' || $paymentAccount->user_id === $user->id;
        });

        // gate for seeing an order, which would be if it's the user's order or if the user is the director, senior staff, or billing staff of the facility
        
        Gate::define('see-order', function ($user, $order) {
            
            return $user->role === 'admin' || $order->user_id === $user->id || $order->facility->seniorStaff->contains($user) || $order->facility->billingStaff->contains($user);
        });

        // DB::listen(function($query) {
        //     File::append(
        //         storage_path('/logs/query.log'),
        //         '[' . date('Y-m-d H:i:s') . ']' . PHP_EOL . $query->sql . ' [' . implode(', ', $query->bindings) . ']' . PHP_EOL . PHP_EOL
        //     );
        // });

        


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
