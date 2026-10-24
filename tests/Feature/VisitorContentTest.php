<?php

namespace Tests\Feature;

use App\Models\SportsResult;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class VisitorContentTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_sports_page_separates_upcoming_fixtures_from_played_matches(): void
    {
        $this->travelTo('2026-10-24 12:00:00');

        SportsResult::create([
            'competition' => 'Played League',
            'home_team' => 'Played Home',
            'away_team' => 'Played Away',
            'home_score' => 2,
            'away_score' => 1,
            'played_at' => now()->subDay(),
            'is_published' => true,
        ]);
        SportsResult::create([
            'competition' => 'Upcoming League',
            'home_team' => 'Upcoming Home',
            'away_team' => 'Upcoming Away',
            'home_score' => 0,
            'away_score' => 0,
            'played_at' => now()->addDay(),
            'is_published' => true,
        ]);
        SportsResult::create([
            'competition' => 'Unpublished League',
            'home_team' => 'Hidden Home',
            'away_team' => 'Hidden Away',
            'home_score' => 0,
            'away_score' => 0,
            'played_at' => now()->addDays(2),
            'is_published' => false,
        ]);

        $this->get(route('sports.results'))
            ->assertOk()
            ->assertSeeInOrder([
                'المباريات القادمة',
                'قادمة',
                'Upcoming Home',
                'المباريات التي لُعبت',
                'انتهت',
                'Played Home',
            ])
            ->assertSee('class="col-lg-6 mx-auto"', false)
            ->assertSee('2')
            ->assertDontSee('Hidden Home')
            ->assertDontSee('Unpublished League');
    }

    public function test_home_page_separates_upcoming_fixtures_from_played_matches(): void
    {
        Http::fake();
        $this->travelTo('2026-10-24 12:00:00');

        SportsResult::create([
            'competition' => 'Played League',
            'home_team' => 'Played Home',
            'away_team' => 'Played Away',
            'home_score' => 2,
            'away_score' => 1,
            'played_at' => now()->subDay(),
            'is_published' => true,
        ]);
        SportsResult::create([
            'competition' => 'Upcoming League',
            'home_team' => 'Upcoming Home',
            'away_team' => 'Upcoming Away',
            'home_score' => 0,
            'away_score' => 0,
            'played_at' => now()->addDay(),
            'is_published' => true,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSeeInOrder([
                'المباريات القادمة',
                'قادمة',
                'Upcoming Home',
                'المباريات التي لُعبت',
                'انتهت',
                'Played Home',
            ]);
    }

    public function test_match_scheduled_at_the_current_time_is_shown_as_upcoming(): void
    {
        $this->travelTo('2026-10-24 12:00:00');

        SportsResult::create([
            'competition' => 'Kickoff League',
            'home_team' => 'Kickoff Home',
            'away_team' => 'Kickoff Away',
            'home_score' => 0,
            'away_score' => 0,
            'played_at' => now(),
            'is_published' => true,
        ]);

        $this->get(route('sports.results'))
            ->assertOk()
            ->assertSeeInOrder([
                'المباريات القادمة',
                'قادمة',
                'Kickoff Home',
                'المباريات التي لُعبت',
            ])
            ->assertSee('class="col-lg-6 mx-auto"', false)
            ->assertSee('لا توجد نتائج مباريات منشورة حاليًا.');
    }
}
