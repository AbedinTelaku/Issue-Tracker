<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Issue>
 */
class IssueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titles = [
            'Login page not responsive on mobile devices',
            'Add dark mode toggle to user settings',
            'Implement email notifications for new comments',
            'Fix memory leak in data processing module',
            'Create API documentation for developers',
            'Optimize database queries for better performance',
            'Add two-factor authentication',
            'Update copyright information in footer',
            'Implement search functionality',
            'Fix broken links in navigation menu',
            'Add export functionality to reports',
            'Improve error handling in payment system',
            'Create user onboarding tutorial',
            'Fix timezone issues in calendar view',
            'Add bulk delete functionality',
        ];

        return [
            'project_id' => Project::factory(),
            'title' => fake()->randomElement($titles),
            'description' => fake()->paragraphs(fake()->numberBetween(1, 3), true),
            'status' => fake()->randomElement(['open', 'in_progress', 'closed']),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'due_date' => fake()->optional(0.6)->dateTimeBetween('now', '+3 months'),
            'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
