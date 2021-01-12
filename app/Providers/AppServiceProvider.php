<?php

namespace App\Providers;

use App\Helpers\AppHelper;
use App\Repositories\UserRepository;
use Faker\Provider\bg_BG\PhoneNumber;
use Illuminate\Support\ServiceProvider;
use libphonenumber\PhoneNumberUtil;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AppHelper::class, function() {
            return new AppHelper();
        });

        $this->app->singleton(UserRepository::class, function($app) {
            return new UserRepository($app->make(AppHelper::class));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
