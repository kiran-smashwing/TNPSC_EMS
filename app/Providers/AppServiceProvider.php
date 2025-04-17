<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Mail;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public const HOME = '/dashboard';

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
        Blade::directive('hasPermission', function ($expression) {
            return "<?php if(app(App\Services\AuthorizationService::class)->hasPermission(session('auth_role'), {$expression})): ?>";
        });

        Blade::directive('endhasPermission', function () {
            return "<?php endif; ?>";
        });
        if (app()->environment('local', 'staging', 'testing')) {
            Mail::alwaysTo('kiran@smashwing.com'); // Change this to your desired static email
        }
    }
}
