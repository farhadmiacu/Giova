<?php

namespace App\Providers;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share $setting with all views that use backend.master
        // View::composer('backend.master', function ($view) {
        //     dd(SystemSetting::first());
        //     // $view->with('systemSetting', SystemSetting::first());
        // });
        View::composer('backend.*', function ($view) {
            $view->with('systemSetting', SystemSetting::first());
        });
    }
}
