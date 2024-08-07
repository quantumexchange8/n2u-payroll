<?php

namespace App\Providers;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;


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

        Validator::extend('shift_end_after_start', function ($attribute, $value, $parameters, $validator) {
            // $value is the value of shift_end
            // $parameters[0] is the value of shift_start
            $shiftStart = $parameters[0];
            $shiftEnd = $value;

            if (!preg_match('/^\d{2}:\d{2}$/', $shiftStart) || !preg_match('/^\d{2}:\d{2}$/', $shiftEnd)) {
                return false; // Return false if time format is invalid
            }

            // Convert times to Carbon objects for easier comparison
            $startTime = Carbon::createFromFormat('H:i', $shiftStart);
            $endTime = Carbon::createFromFormat('H:i', $shiftEnd);

            // Check if shift_end is after shift_start or if shift_end is past midnight and shift_start is in the evening
            return $endTime->greaterThan($startTime) || ($endTime->lessThan($startTime) && $endTime->greaterThan($startTime->copy()->addHours(4)));
        });

        Validator::replacer('shift_end_after_start', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':shift_start', $parameters[0], $message);
        });
    }
}
