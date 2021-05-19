<?php

namespace App\Providers;

use App\Models\Punto;
use App\Observers\PuntoObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //

        Punto::observe(PuntoObserver::class);
    }
}
