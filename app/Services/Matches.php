<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Matches
{
    /** Matchs du jour pour les ligues configurées (cache 2 min, repli sur l'ancien cache si l'API échoue). */
    public function today(): array
    {
        return Cache::remember('matches_today', 120, function () {
            try {
                $res = Http::timeout(6)
                    ->withHeaders(['x-apisports-key' => config('services.sports.key')])
                    ->get(config('services.sports.base').'/fixtures', ['date' => now()->toDateString()]);

                if (! $res->successful()) {
                    return Cache::get('matches_today_stale', []);
                }

                $wanted = array_map('intval', config('services.sports.leagues'));

                $data = collect($res->json('response', []))
                    ->filter(fn ($f) => in_array($f['league']['id'], $wanted, true))
                    ->map(fn ($f) => [
                        'league' => $f['league']['name'],
                        'home' => $f['teams']['home']['name'],
                        'away' => $f['teams']['away']['name'],
                        'home_logo' => $f['teams']['home']['logo'],
                        'away_logo' => $f['teams']['away']['logo'],
                        'goals_h' => $f['goals']['home'],
                        'goals_a' => $f['goals']['away'],
                        'status' => $f['fixture']['status']['short'],   // NS, 1H, HT, 2H, FT...
                        'elapsed' => $f['fixture']['status']['elapsed'],
                        'time' => Carbon::parse($f['fixture']['date'])
                            ->timezone('Africa/Tunis')->format('H:i'),
                    ])
                    ->values()->all();

                Cache::forever('matches_today_stale', $data);

                return $data;
            } catch (\Throwable $e) {
                Log::warning('Matches API: '.$e->getMessage());

                return Cache::get('matches_today_stale', []);
            }
        });
    }
}
