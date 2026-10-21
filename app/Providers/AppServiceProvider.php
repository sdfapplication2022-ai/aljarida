<?php

namespace App\Providers;

use App\Models\Category;
use Cloudinary\Configuration\Configuration;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(UrlGenerator $url): void
    {
        // 1. Forcer HTTPS en production (pour Render)
        if (config('app.env') === 'production') {
            $url->forceScheme('https');
        }

        // 2. Configuration de Cloudinary
        Configuration::instance([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
            'url' => [
                'secure' => true, // Force les URL en HTTPS
            ],
        ]);

        // 3. Optimisation du menu
        View::composer(['partials.header', 'partials.footer', 'partials.article-sidebar'], function ($view) {
            $menuCategories = Cache::remember('menu_categories', 3600, function () {
                return Category::query()
                    ->select('id', 'name', 'slug')
                    ->orderBy('name')
                    ->get();
            });
            $view->with('menuCategories', $menuCategories);
        });
    }
}
