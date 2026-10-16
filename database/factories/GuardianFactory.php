<?php

namespace Database\Factories;

use App\Models\Guardian;
use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Guardian>
 */
class GuardianFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Guardian>
     */
    protected $model = Guardian::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->safeEmail(),
            'occupation' => fake()->jobTitle(),
            'address' => fake()->address(),
            'national_id' => fake()->numerify('NID-##########'),
            'photo' => null,
        ];
    }
}
