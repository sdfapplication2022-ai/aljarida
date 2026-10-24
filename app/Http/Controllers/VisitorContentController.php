<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\LocalEvent;
use App\Models\SportsResult;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class VisitorContentController extends Controller
{
    public function events(): View
    {
        $events = LocalEvent::where('is_published', true)
            ->where(function (Builder $query): void {
                $query->where('starts_at', '>=', now())
                    ->orWhere(function (Builder $ending): void {
                        $ending->whereNotNull('ends_at')->where('ends_at', '>=', now());
                    });
            })
            ->orderBy('starts_at')
            ->paginate(12);

        return view('pages.local-events', compact('events'));
    }

    public function sports(): View
    {
        $now = now();
        $upcomingResults = SportsResult::query()
            ->published()
            ->where('played_at', '>=', $now)
            ->orderBy('played_at')
            ->orderBy('id')
            ->paginate(6, ['*'], 'upcoming_page')
            ->withQueryString();
        $playedResults = SportsResult::query()
            ->published()
            ->where('played_at', '<', $now)
            ->orderByDesc('played_at')
            ->orderByDesc('id')
            ->paginate(6, ['*'], 'played_page')
            ->withQueryString();

        return view('pages.sports-results', compact('upcomingResults', 'playedResults'));
    }

    public function cityNews(Request $request): View
    {
        $data = $request->validate([
            'city' => 'nullable|string|max:100',
        ]);
        $city = trim((string) ($data['city'] ?? ''));

        $cities = Article::published()
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        $articles = Article::published()
            ->with(['category', 'user'])
            ->when($city !== '', fn (Builder $query): Builder => $query->where('city', $city))
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('pages.city-news', compact('articles', 'cities', 'city'));
    }
}
