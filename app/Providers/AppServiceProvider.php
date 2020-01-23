<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Validators\ResetValidator;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Validator::resolver(function($translator, $data, $rules, $messages)
        {
            return new ResetValidator($translator, $data, $rules, $messages);
        });
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
