<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    const getCrossRefRateLimit = 1;
    const getPubMedAPIRate = 1;

    public static function getApiemail()
    {
        return 'afletcher53@gmail.com';
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
