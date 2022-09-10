<?php

namespace App\Providers;

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
        //ownerから始まるURLの場合
        if (request()->is('owner')) {
            //owner用のクッキーを使う
            config(['session.cookie' => config('session.cookie_owner')]);
        }

        //adminから始まるURLの場合
        if (request()->is('admin')) {
            //admin用のクッキーを使う
            config(['session.cookie' => config('session.cookie_admin')]);
        }
    }
}
