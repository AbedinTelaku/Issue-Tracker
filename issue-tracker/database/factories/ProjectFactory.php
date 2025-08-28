<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-6 months', '+1 month');
        $deadline = fake()->optional(0.7)->dateTimeBetween($startDate, '+1 year');

        return [
            'name' => fake()->words(3, true),
            'description' => fake()->paragraphs(2, true),
            'start_date' => fake()->optional(0.8)->dateTimeBetween('-6 months', '+1 month'),
            'deadline' => $deadline,
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
