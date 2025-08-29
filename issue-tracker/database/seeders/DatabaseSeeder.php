<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
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
        // Create users first
        $users = User::factory(6)->create();
        
        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);
        
        $allUsers = $users->concat([$admin]);

        // Create tags
        $tags = Tag::factory(10)->create();

        // Create projects with issues
        Project::factory(5)->create()->each(function ($project) use ($tags, $allUsers) {
            // Assign random project owner
            $project->update(['user_id' => $allUsers->random()->id]);
            
            // Create 3-8 issues per project
            $issues = Issue::factory(rand(3, 8))->create([
                'project_id' => $project->id,
            ]);

            $issues->each(function ($issue) use ($tags, $allUsers) {
                // Attach 0-3 random tags to each issue
                $randomTags = $tags->random(rand(0, 3));
                $issue->tags()->attach($randomTags);

                // Assign 0-2 random users to each issue
                $randomUsers = $allUsers->random(rand(0, 2));
                $issue->assignedUsers()->attach($randomUsers);

                // Create 0-5 comments per issue
                Comment::factory(rand(0, 5))->create([
                    'issue_id' => $issue->id,
                ]);
            });
        });

        $this->command->info('Database seeded successfully!');
        $this->command->info('Created:');
        $this->command->info('- ' . User::count() . ' users');
        $this->command->info('- ' . Project::count() . ' projects');
        $this->command->info('- ' . Issue::count() . ' issues');
        $this->command->info('- ' . Tag::count() . ' tags');
        $this->command->info('- ' . Comment::count() . ' comments');
        $this->command->info('');
        $this->command->info('Admin credentials:');
        $this->command->info('Email: admin@example.com');
        $this->command->info('Password: password');
    }
}
