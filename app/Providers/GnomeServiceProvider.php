<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\GnomeService;

class GnomeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('GnomeService', GnomeService::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
