<?php

namespace App\Providers;

use App\Models\Program;
use App\Models\Setting;
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
        View::composer(['components.layouts.app', 'components.site.*', 'pages.*'], function ($view): void {
            $view->with([
                'navPrograms' => once(fn () => Program::query()->published()->get(['id', 'slug', 'icon', 'title'])),
                'site' => once(fn () => Setting::allValues()),
            ]);
        });
    }
}
