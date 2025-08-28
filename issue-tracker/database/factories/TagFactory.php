<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $colors = [
            '#dc3545', '#fd7e14', '#ffc107', '#198754', '#20c997',
            '#0dcaf0', '#0d6efd', '#6610f2', '#6f42c1', '#d63384',
            '#e83e8c', '#495057', '#6c757d', '#adb5bd'
        ];

        $tags = [
            'Bug', 'Feature', 'Enhancement', 'Documentation', 'Testing',
            'Frontend', 'Backend', 'API', 'Database', 'Security',
            'Performance', 'UI/UX', 'Mobile', 'Critical', 'Low Priority'
        ];

        return [
            'name' => fake()->unique()->randomElement($tags),
            'color' => fake()->randomElement($colors),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
