<?php

namespace App\Providers;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        if (App::environment('production')) {
            resolve(\Illuminate\Routing\UrlGenerator::class)->forceScheme('https');
            $this->app['request']->server->set('HTTPS', true);
        } else {
            Mail::alwaysTo('developer@currenttech.pro');
        }
    }
}
