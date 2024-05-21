<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfessionalExperienceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => fn() => User::factory(),

            'company_name' => $this->faker->company(),
            'position' => $this->faker->text(),
            'description' => $this->faker->paragraph(),
            'start_date' => $this->faker->dateTimeBetween('-10 years', 'now'),
            'end_date' => $this->faker->dateTimeBetween('now', '+10 years'),
            'is_current' => false,
            'location' => $this->faker->address,
            'work_model' => $this->faker->randomElement(['hybrid', 'onSite', 'homeOffice']),
            'employment_type' => $this->faker->randomElement(['full-time', 'part-time']),
        ];
    }
}
