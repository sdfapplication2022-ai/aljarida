<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Météo, horaires de prière et taux de change pour Tunis.
 * Les données sont récupérées côté serveur, mises en cache, et la dernière valeur connue
 * est servie si le service externe est indisponible (aucune erreur côté lecteur).
 */
class Widgets
{
    private const TZ = 'Africa/Tunis';

    private const PRAYERS = [
        'Fajr' => 'الفجر', 'Sunrise' => 'الشروق', 'Dhuhr' => 'الظهر',
        'Asr' => 'العصر', 'Maghrib' => 'المغرب', 'Isha' => 'العشاء',
    ];

    /** Devises affichées : 1 unité = X dinars. */
    private const CURRENCIES = [
        'EUR' => 'يورو', 'USD' => 'دولار أمريكي', 'GBP' => 'جنيه إسترليني', 'SAR' => 'ريال سعودي',
    ];

    public function all(bool $force = false): array
    {
        return [
            'weather' => $this->weather($force),
            'prayer'  => $this->prayer($force),
            'rates'   => $this->rates($force),
        ];
    }

    /** Cache + dernière valeur connue + pause de 2 min après un échec (évite de bloquer chaque visite). */
    private function remember(string $key, int $ttl, bool $force, callable $fetch): ?array
    {
        if (! $force) {
            if ($hit = Cache::get($key)) {
                return $hit;
            }
            if (Cache::has($key.':fail')) {
                return Cache::get($key.':stale');
            }
        }

        try {
            $data = $fetch();
        } catch (\Throwable $e) {
            Log::warning("Widget {$key} : ".$e->getMessage());
            $data = null;
        }

        if (is_array($data) && $data) {
            Cache::put($key, $data, $ttl);
            Cache::put($key.':stale', $data, 86400 * 3);
            Cache::forget($key.':fail');

            return $data;
        }

        Cache::put($key.':fail', 1, 120);

        return Cache::get($key.':stale');
    }

    // ------------------------------------------------------------------ Météo (Open-Meteo)

    private function weather(bool $force): ?array
    {
        return $this->remember('widgets:weather', 1800, $force, function () {
            $apiKey = config('services.open_meteo.key'); // optionnel, requis pour un usage commercial
            $base   = $apiKey ? 'https://customer-api.open-meteo.com' : 'https://api.open-meteo.com';

            $j = Http::timeout(4)->acceptJson()->get($base.'/v1/forecast', array_filter([
                'latitude'      => 36.8065,
                'longitude'     => 10.1815,
                'current'       => 'temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m',
                'daily'         => 'temperature_2m_max,temperature_2m_min',
                'timezone'      => self::TZ,
                'forecast_days' => 1,
                'apikey'        => $apiKey,
            ]))->throw()->json();

            $c = $j['current'] ?? null;
            if (! $c || ! isset($c['temperature_2m'])) {
                return null;
            }

            [$label, $icon] = $this->weatherCode((int) ($c['weather_code'] ?? -1));

            return [
                'temp'     => (int) round($c['temperature_2m']),
                'humidity' => (int) round($c['relative_humidity_2m'] ?? 0),
                'wind'     => (int) round($c['wind_speed_10m'] ?? 0),
                'max'      => (int) round(data_get($j, 'daily.temperature_2m_max.0', $c['temperature_2m'])),
                'min'      => (int) round(data_get($j, 'daily.temperature_2m_min.0', $c['temperature_2m'])),
                'label'    => $label,
                'icon'     => $icon,
            ];
        });
    }

    /** Codes météo WMO -> [libellé arabe, icône Font Awesome]. */
    private function weatherCode(int $code): array
    {
        return match (true) {
            $code === 0                        => ['صافٍ', 'fa-sun'],
            $code === 1                        => ['صافٍ غالبًا', 'fa-cloud-sun'],
            $code === 2                        => ['غائم جزئيًا', 'fa-cloud-sun'],
            $code === 3                        => ['غائم', 'fa-cloud'],
            in_array($code, [45, 48])          => ['ضباب', 'fa-smog'],
            in_array($code, [51, 53, 55, 56, 57]) => ['رذاذ', 'fa-cloud-rain'],
            in_array($code, [61, 63, 65, 66, 67]) => ['أمطار', 'fa-cloud-showers-heavy'],
            in_array($code, [71, 73, 75, 77, 85, 86]) => ['ثلوج', 'fa-snowflake'],
            in_array($code, [80, 81, 82])      => ['زخات مطر', 'fa-cloud-showers-heavy'],
            in_array($code, [95, 96, 99])      => ['عاصفة رعدية', 'fa-bolt'],
            default                            => ['طقس متغيّر', 'fa-cloud'],
        };
    }

    // ------------------------------------------------------------------ Prière (AlAdhan, méthode Tunisie)

    private function prayer(bool $force): ?array
    {
        $now  = Carbon::now(self::TZ);
        $date = $now->format('d-m-Y');

        $data = $this->remember('widgets:prayer:'.$date, 86400, $force, function () use ($date) {
            $j = Http::timeout(4)->acceptJson()
                ->get("https://api.aladhan.com/v1/timingsByCity/{$date}", [
                    'city' => 'Tunis', 'country' => 'Tunisia', 'method' => 18, // 18 = Tunisie (ministère des Affaires religieuses)
                ])->throw()->json();

            $t = data_get($j, 'data.timings');
            if (! is_array($t)) {
                return null;
            }

            $times = [];
            foreach (array_keys(self::PRAYERS) as $k) {
                if (empty($t[$k])) {
                    return null;
                }
                $times[$k] = substr($t[$k], 0, 5); // "05:12 (CET)" -> "05:12"
            }

            $h = data_get($j, 'data.date.hijri');

            return [
                'times' => $times,
                'hijri' => $h
                    ? trim(($h['weekday']['ar'] ?? '').' '.(int) $h['day'].' '.($h['month']['ar'] ?? '').' '.$h['year'])
                    : null,
            ];
        });

        if (! $data) {
            return null;
        }

        $data['labels'] = self::PRAYERS;
        $data['next']   = $this->nextPrayer($data['times'], $now); // recalculé à chaque visite

        return $data;
    }

    private function nextPrayer(array $times, Carbon $now): array
    {
        foreach (['Fajr', 'Dhuhr', 'Asr', 'Maghrib', 'Isha'] as $k) {
            $at = Carbon::parse($now->toDateString().' '.$times[$k], self::TZ);
            if ($at->greaterThan($now)) {
                return ['key' => $k, 'at' => $at->toIso8601String()];
            }
        }

        // Après l'Isha : Fajr du lendemain (horaire du jour, à quelques minutes près)
        $at = Carbon::parse($now->toDateString().' '.$times['Fajr'], self::TZ)->addDay();

        return ['key' => 'Fajr', 'at' => $at->toIso8601String()];
    }

    // ------------------------------------------------------------------ Change (open.er-api.com)

    private function rates(bool $force): ?array
    {
        return $this->remember('widgets:rates', 21600, $force, function () {
            $j = Http::timeout(4)->acceptJson()->get('https://open.er-api.com/v6/latest/USD')->throw()->json();
            $r = $j['rates'] ?? [];

            if (($j['result'] ?? '') !== 'success' || empty($r['TND'])) {
                return null;
            }

            $rows = [];
            foreach (self::CURRENCIES as $code => $name) {
                if (! empty($r[$code])) {
                    $rows[] = ['code' => $code, 'name' => $name, 'value' => round($r['TND'] / $r[$code], 3)];
                }
            }

            return $rows ? [
                'rows'    => $rows,
                'updated' => Carbon::createFromTimestamp($j['time_last_update_unix'] ?? time(), self::TZ)->format('Y/m/d'),
            ] : null;
        });
    }
}
