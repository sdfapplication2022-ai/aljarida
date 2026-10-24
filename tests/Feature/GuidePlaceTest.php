<?php

namespace Tests\Feature;

use App\Models\GuidePlace;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class GuidePlaceTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_staff_sees_the_article_style_guide_place_form(): void
    {
        $staff = User::factory()->create(['role' => 'editor']);

        $this->actingAs($staff)
            ->get(route('admin.crud.create', 'guide-places'))
            ->assertOk()
            ->assertViewIs('admin.guide-places.form')
            ->assertSee('id="guide-place-form"', false)
            ->assertSee('name="is_published"', false)
            ->assertSee('name="image"', false)
            ->assertSee('name="description"', false);
    }

    public function test_public_listing_filters_places_and_hides_unpublished_records(): void
    {
        $matchingPlace = GuidePlace::factory()->create([
            'name' => 'Student Housing North',
            'slug' => 'student-housing-north',
            'type' => 'housing',
            'city' => 'جندوبة',
        ]);
        GuidePlace::factory()->create([
            'name' => 'Student Housing South',
            'type' => 'housing',
            'city' => 'بوسالم',
        ]);
        GuidePlace::factory()->create([
            'name' => 'Student Cafe',
            'type' => 'cafe',
            'city' => 'جندوبة',
        ]);
        GuidePlace::factory()->create([
            'name' => 'Hidden Student Housing',
            'is_published' => false,
        ]);

        $this->get(route('guide-places.index', [
            'q' => 'Student',
            'type' => 'housing',
            'city' => 'جندوبة',
        ]))
            ->assertOk()
            ->assertSee($matchingPlace->name)
            ->assertDontSee('Student Housing South')
            ->assertDontSee('Student Cafe')
            ->assertDontSee('Hidden Student Housing');
    }

    public function test_public_detail_uses_slug_and_rejects_unpublished_places(): void
    {
        $publishedPlace = GuidePlace::factory()->create(['slug' => 'published-place']);
        $unpublishedPlace = GuidePlace::factory()->create([
            'slug' => 'unpublished-place',
            'is_published' => false,
        ]);

        $this->get(route('guide-places.show', $publishedPlace))
            ->assertOk()
            ->assertSee($publishedPlace->name);

        $this->get(route('guide-places.show', $unpublishedPlace))
            ->assertNotFound();
    }

    public function test_public_detail_sanitizes_place_description(): void
    {
        $place = GuidePlace::factory()->create([
            'slug' => 'safe-description',
            'description' => '<p>Useful information<script>alert(1)</script></p>',
        ]);

        $this->get(route('guide-places.show', $place))
            ->assertOk()
            ->assertSee('Useful information')
            ->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_public_detail_shows_an_embedded_map_below_related_places_when_coordinates_exist(): void
    {
        $place = GuidePlace::factory()->create([
            'slug' => 'mapped-place',
            'latitude' => 36.5,
            'longitude' => 8.7,
            'address' => 'وسط جندوبة',
        ]);

        $this->get(route('guide-places.show', $place))
            ->assertOk()
            ->assertSee('title="خريطة موقع '.$place->name.'"', false)
            ->assertSee('https://maps.google.com/maps?q=36.5000000,8.7000000', false)
            ->assertSee('فتح في خرائط Google');
    }

    public function test_public_detail_hides_map_when_coordinates_are_missing(): void
    {
        $place = GuidePlace::factory()->create([
            'slug' => 'place-without-coordinates',
            'latitude' => null,
            'longitude' => null,
        ]);

        $this->get(route('guide-places.show', $place))
            ->assertOk()
            ->assertDontSee('title="خريطة موقع', false);
    }

    public function test_staff_can_create_a_place_with_json_amenities(): void
    {
        $staff = User::factory()->create(['role' => 'editor']);

        $this->actingAs($staff)
            ->post(route('admin.crud.store', 'guide-places'), [
                'name' => 'Student Housing',
                'type' => 'housing',
                'city' => 'جندوبة',
                'price_from' => 300,
                'latitude' => 36.5,
                'longitude' => 8.7,
                'amenities' => '["واي فاي","مطبخ"]',
                'is_verified' => '1',
                'is_published' => '1',
            ])
            ->assertRedirect(route('admin.crud.index', 'guide-places'));

        $place = GuidePlace::where('slug', 'Student-Housing')->firstOrFail();

        $this->assertSame(['واي فاي', 'مطبخ'], $place->amenities);
        $this->assertTrue($place->is_verified);
        $this->assertTrue($place->is_published);
        $this->assertDatabaseHas('guide_places', [
            'id' => $place->id,
            'price_from' => 300,
        ]);
    }

    public function test_non_staff_cannot_manage_guide_places(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->get(route('admin.crud.index', 'guide-places'))
            ->assertForbidden();
    }

    public function test_admin_rejects_amenities_that_are_not_a_json_list_of_strings(): void
    {
        $staff = User::factory()->create(['role' => 'editor']);

        $this->actingAs($staff)
            ->from(route('admin.crud.create', 'guide-places'))
            ->post(route('admin.crud.store', 'guide-places'), [
                'name' => 'Invalid Amenities',
                'type' => 'housing',
                'city' => 'جندوبة',
                'amenities' => '{"wifi":true}',
            ])
            ->assertRedirect(route('admin.crud.create', 'guide-places'))
            ->assertSessionHasErrors('amenities');

        $this->assertDatabaseMissing('guide_places', ['name' => 'Invalid Amenities']);
    }

    public function test_admin_rejects_coordinates_outside_valid_ranges(): void
    {
        $staff = User::factory()->create(['role' => 'editor']);

        $this->actingAs($staff)
            ->from(route('admin.crud.create', 'guide-places'))
            ->post(route('admin.crud.store', 'guide-places'), [
                'name' => 'Invalid Coordinates',
                'type' => 'housing',
                'city' => 'جندوبة',
                'latitude' => 91,
                'longitude' => 8,
            ])
            ->assertRedirect(route('admin.crud.create', 'guide-places'))
            ->assertSessionHasErrors('latitude');

        $this->assertDatabaseMissing('guide_places', ['name' => 'Invalid Coordinates']);
    }
}
