<?php

namespace App\Providers;

use App\Helpers\CartHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Facades\Module;

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
        app()->singleton(CartHelper::class);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // require_once __DIR__."../Helpers/helper.php";
        // $module = Module::find('blog');
        // $module->disable();

    }
}
