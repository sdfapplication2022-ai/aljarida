<?php

namespace App\Http\Controllers;

use App\Models\{Article, Category, Tag};
use Illuminate\Support\Facades\Cache;

class SeoController extends Controller
{
    public function sitemap()
    {
        $xml = Cache::remember('sitemap_xml', 3600, function () {
            return view('seo.sitemap', [
                'articles'   => Article::published()->select('slug', 'updated_at', 'published_at')
                                    ->latest('published_at')->limit(5000)->get(),
                'categories' => Category::select('slug', 'updated_at')->get(),
                'tags'       => Tag::select('slug', 'updated_at')->get(),
            ])->render();
        });

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    public function feed()
    {
        $xml = Cache::remember('rss_feed', 600, function () {
            return view('seo.feed', [
                'articles' => Article::published()->with(['category', 'user'])
                                ->latest('published_at')->take(30)->get(),
            ])->render();
        });

        return response($xml, 200, ['Content-Type' => 'application/rss+xml; charset=UTF-8']);
    }

    public function robots()
    {
        $txt = "User-agent: *\nDisallow: /admin\nDisallow: /search\n\nSitemap: ".route('sitemap')."\n";
        return response($txt, 200, ['Content-Type' => 'text/plain']);
    }
}
