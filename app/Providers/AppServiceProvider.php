<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
                    $menuCategories = Category::query()
                        ->select('id', 'name', 'slug')
                        ->orderBy('name')
                        ->get();

                    $view->with('menuCategories', $menuCategories);
                });
    }
}
