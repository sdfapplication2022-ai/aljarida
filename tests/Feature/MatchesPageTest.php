<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MatchesPageTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.sports.leagues' => ['202']]);
    }

    public function test_matches_page_lists_todays_fixtures(): void
    {
        $this->fakeSportsApi();

        $this->get(route('matches.index'))
            ->assertOk()
            ->assertSee('Espérance')
            ->assertSee('Étoile Sportive')
            ->assertSee('Ligue 1')
            ->assertSee(route('api.matches'), false);
    }

    public function test_home_page_shows_todays_fixtures_with_link_to_matches_page(): void
    {
        $this->fakeSportsApi();

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Espérance')
            ->assertSee('Étoile Sportive')
            ->assertSee(route('matches.index'), false);
    }

    public function test_matches_page_shows_an_empty_state_when_the_api_fails(): void
    {
        Http::fake(['*' => Http::response(null, 500)]);

        $this->get(route('matches.index'))
            ->assertOk()
            ->assertSee('لا توجد مباريات مبرمجة اليوم في الدوريات المتابعة.');
    }

    private function fakeSportsApi(): void
    {
        Http::fake(function (Request $request) {
            if (! str_contains($request->url(), 'api-sports.io')) {
                return Http::response([], 200);
            }

            return Http::response([
                'response' => [[
                    'fixture' => [
                        'date' => now()->toIso8601String(),
                        'status' => ['short' => 'NS', 'elapsed' => null],
                    ],
                    'league' => ['id' => 202, 'name' => 'Ligue 1'],
                    'teams' => [
                        'home' => ['name' => 'Espérance', 'logo' => 'https://example.test/home.png'],
                        'away' => ['name' => 'Étoile Sportive', 'logo' => 'https://example.test/away.png'],
                    ],
                    'goals' => ['home' => null, 'away' => null],
                ]],
            ]);
        });
    }
}
