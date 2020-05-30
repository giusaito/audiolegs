<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;

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
       \Schema::defaultStringLength(191);

       Activity::saving(function (Activity $activity) {
        
            $activity->properties = $activity->properties->put('ip', request()->ip());

            $activity->properties = $activity->properties->put('user_agent', request()->header('User-Agent'));
            
            $activity->properties = $activity->properties->put('url', \request()->fullUrl());

            $activity->properties = $activity->properties->put('method', \request()->method());
        });
    }
}
