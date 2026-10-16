<?php

namespace Database\Factories;

use App\Enums\SchoolStatus;
use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<School>
 */
class SchoolFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<School>
     */
    protected $model = School::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->company().' Academy';

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(100, 999),
            'code' => 'SCH-'.fake()->unique()->numberBetween(1000, 9999),
            'status' => SchoolStatus::Active,
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'country' => 'United States',
            'postal_code' => fake()->postcode(),
            'logo' => null,
            'website' => 'https://'.fake()->domainName(),
            'established_year' => fake()->numberBetween(1985, 2024),
            'timezone' => 'America/New_York',
            'currency' => 'USD',
        ];
    }

    /**
     * Indicate that the school is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => SchoolStatus::Inactive,
        ]);
    }

    /**
     * Indicate that the school is suspended.
     */
    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => SchoolStatus::Suspended,
        ]);
    }

    /**
     * Indicate that the school is pending verification.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => SchoolStatus::Pending,
        ]);
    }
}
