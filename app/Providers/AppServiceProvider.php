<?php

namespace App\Providers;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
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
            $shiftStartKey = str_replace('*', '0', $parameters[0]);
            $shiftStart = $validator->getValue($shiftStartKey);
            $shiftEnd = $value;

            if (!preg_match('/^\d{2}:\d{2}$/', $shiftStart) || !preg_match('/^\d{2}:\d{2}$/', $shiftEnd)) {
                return false;
            }

            $startTime = Carbon::createFromFormat('H:i', $shiftStart);
            $endTime = Carbon::createFromFormat('H:i', $shiftEnd);

            Log::info("Start Time: $startTime, End Time: $endTime");
            if ($endTime->greaterThan($startTime)) {
                return true;
            }
        
            $shiftEndCopy  = $endTime->copy()->addDay();
            $shiftStartCopy = $startTime->copy()->addHours(4);
            Log::info("Start Time: $shiftStartCopy, End Time: $shiftEndCopy");
            if ($startTime->lessThan($shiftEndCopy) && $shiftEndCopy->lessThanOrEqualTo($shiftStartCopy)) {
                return true;
            }
        });

        Validator::replacer('shift_end_after_start', function ($message, $attribute, $rule, $parameters) {
            $shiftStartAttribute = str_replace('*', '0', $parameters[0]);
            $shiftStart = request()->input($shiftStartAttribute);

            return str_replace(':shift_schedules.*.shift_start', $shiftStart, $message);
        });
    }
}
