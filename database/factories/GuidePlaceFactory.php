<?php

namespace Database\Factories;

use App\Models\GuidePlace;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<GuidePlace>
 */
class GuidePlaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->company().' '.fake()->word();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'type' => fake()->randomElement(GuidePlace::TYPES),
            'city' => 'جندوبة',
            'address' => fake()->address(),
            'near_campus' => fake()->word(),
            'description' => fake()->paragraph(),
            'price_from' => fake()->numberBetween(10, 500),
            'price_unit' => 'شهري',
            'amenities' => ['واي فاي'],
            'is_verified' => false,
            'is_published' => true,
        ];
    }
}
