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
        // 1. Forcer HTTPS en production
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
                'secure' => true,
            ],
        ]);

        // 3. Injection des catégories (Sécurisée et compatible avec $c->slug)
        View::composer([
            'pages.home',
            'partials.header',
            'partials.footer',
            'partials.article-sidebar'
        ], function ($view) {

            // On stocke un tableau dans le cache
            $categoriesArray = Cache::remember('menu_categories', 3600, function () {
                return Category::query()
                    ->select('id', 'name', 'slug')
                    ->orderBy('name')
                    ->get()
                    ->toArray();
            });

            // On transforme chaque élément du tableau en objet (stdClass)
            // pour que $c->slug fonctionne parfaitement dans vos vues Blade
            $categoriesObjects = collect($categoriesArray)->map(function ($item) {
                return (object) $item;
            });

            $view->with('menuCategories', $categoriesObjects);
        });
    }
}
