<?php

namespace App\Providers;

use App\Models\Company;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;
use App\Models\Role as CustomRole;
use App\Models\Offer;
use App\Observers\CompanyObserver;
use App\Observers\OfferObserver;

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
        Offer::observe(OfferObserver::class);
        Company::observe(CompanyObserver::class);
    }
}
