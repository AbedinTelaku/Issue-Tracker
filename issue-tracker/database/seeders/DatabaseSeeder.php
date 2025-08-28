<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Tag;
use App\Models\Issue;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create tags first
        $tags = Tag::factory(10)->create();

        // Create projects with issues
        Project::factory(5)->create()->each(function ($project) use ($tags) {
            // Create 3-8 issues per project
            $issues = Issue::factory(rand(3, 8))->create([
                'project_id' => $project->id,
            ]);

            $issues->each(function ($issue) use ($tags) {
                // Attach 0-3 random tags to each issue
                $randomTags = $tags->random(rand(0, 3));
                $issue->tags()->attach($randomTags);

                // Create 0-5 comments per issue
                Comment::factory(rand(0, 5))->create([
                    'issue_id' => $issue->id,
                ]);
            });
        });

        $this->command->info('Database seeded successfully!');
        $this->command->info('Created:');
        $this->command->info('- ' . Project::count() . ' projects');
        $this->command->info('- ' . Issue::count() . ' issues');
        $this->command->info('- ' . Tag::count() . ' tags');
        $this->command->info('- ' . Comment::count() . ' comments');
    }
}
