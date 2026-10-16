<?php

namespace Database\Factories;

use App\Models\School;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Student>
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(['male', 'female']);
        $firstName = $gender === 'male' ? fake()->firstNameMale() : fake()->firstNameFemale();

        return [
            'school_id' => School::factory(),
            'admission_number' => 'ADM-'.date('Y').'-'.fake()->unique()->numberBetween(1000, 9999),
            'admission_date' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'first_name' => $firstName,
            'last_name' => fake()->lastName(),
            'gender' => $gender,
            'date_of_birth' => fake()->dateTimeBetween('-18 years', '-5 years')->format('Y-m-d'),
            'blood_group' => fake()->randomElement(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']),
            'religion' => fake()->randomElement(['Islam', 'Christianity', 'Hinduism', 'Buddhism', 'Other']),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'photo' => null,
            'current_address' => fake()->streetAddress().', '.fake()->city(),
            'permanent_address' => fake()->streetAddress().', '.fake()->city(),
            'status' => 'active',
        ];
    }
}
