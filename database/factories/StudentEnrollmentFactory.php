<?php

namespace Database\Factories;

use App\Models\AcademicClass;
use App\Models\AcademicYear;
use App\Models\School;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudentEnrollment>
 */
class StudentEnrollmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<StudentEnrollment>
     */
    protected $model = StudentEnrollment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'student_id' => Student::factory(),
            'academic_year_id' => AcademicYear::factory(),
            'academic_class_id' => AcademicClass::factory(),
            'section_id' => Section::factory(),
            'academic_group_id' => null,
            'roll_number' => (string) fake()->numberBetween(1, 60),
            'status' => 'current',
            'enrolled_at' => now()->format('Y-m-d'),
            'promoted_at' => null,
            'remarks' => fake()->sentence(),
        ];
    }
}
