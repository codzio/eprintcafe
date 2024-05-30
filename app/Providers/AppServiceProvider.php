<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
        Blade::directive('can', function ($expression) {
            list($permission, $module) = explode(',', $expression);

            return "<?php if (can($permission, $module)) : ?>";
        });

        Blade::directive('endcan', function () {
            return '<?php endif; ?>';
        });
    }
}
