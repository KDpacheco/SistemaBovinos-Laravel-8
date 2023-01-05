<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use \Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    // ...

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        } 
    }
}