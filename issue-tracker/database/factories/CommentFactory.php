<?php

namespace Database\Factories;

use App\Models\Issue;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $authors = [
            'John Smith', 'Sarah Johnson', 'Michael Brown', 'Emily Davis',
            'David Wilson', 'Lisa Anderson', 'Robert Taylor', 'Jennifer White',
            'Christopher Lee', 'Amanda Martinez', 'Daniel Thompson', 'Jessica Garcia'
        ];

        $comments = [
            'I can reproduce this issue on my machine as well.',
            'This looks like a CSS specificity problem. Let me investigate.',
            'The fix has been deployed to the staging environment.',
            'Can you provide more details about the browser and OS?',
            'I think we should prioritize this for the next sprint.',
            'This feature would be really helpful for our users.',
            'The issue seems to be related to the recent database migration.',
            'I have a potential solution, will create a pull request.',
            'This is working fine on my end. Can you clear your cache?',
            'We need to add proper error handling for this scenario.',
            'The bug is intermittent, it only happens under specific conditions.',
            'Great work on fixing this! The solution looks good.',
        ];

        return [
            'issue_id' => Issue::factory(),
            'author_name' => fake()->randomElement($authors),
            'body' => fake()->randomElement($comments),
            'created_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ];
    }
}
