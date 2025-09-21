<?php

namespace App\Providers;

use App\Services\BPJS;
use App\Models\Patient;
use App\Services\BuyMedicines;
use App\Observers\PatientObserver;
use App\Services\PharmacyApp;
use App\Services\Stripe;
use Illuminate\Support\Facades\Gate;
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
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super admin') ? true : null;
        });

        $this->app->bind(
            \Backpack\PermissionManager\app\Http\Controllers\UserCrudController::class,
            \App\Http\Controllers\Admin\UserCrudController::class
        );
        // I am adding ability check at the second UserCrudController,
        // so I 'redirect' all call to the first UserCrudController
        // to the second UserCrudController

        $this->app->bind(
            \Backpack\PermissionManager\app\Http\Controllers\RoleCrudController::class,
            \App\Http\Controllers\Admin\RoleCrudController::class
        );

        $this->app->bind(
            \Backpack\PermissionManager\app\Http\Controllers\PermissionCrudController::class,
            \App\Http\Controllers\Admin\PermissionCrudController::class
        );

        $this->app->singleton('bpjs', function ($app) {
            return new BPJS;
        });

        $this->app->singleton('buyMedicines', function ($app) {
            return new BuyMedicines;
        });

        $this->app->singleton('stripe', function ($app) {
            return new Stripe;
        });

        $this->app->singleton('pharmacyApp', function ($app) {
            return new PharmacyApp;
        });

        Patient::observe(PatientObserver::class);
    }
}
