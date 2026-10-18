<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => 'password',
            ],
        );

        $posts = [
            [
                'title' => 'Man Must Explore',
                'slug' => 'man-must-explore',
                'excerpt' => 'A reflection on exploration and our place in the universe.',
                'body' => 'Exploration helps us see the world from a new perspective and reminds us how much remains to be discovered.',
                'featured_image' => 'assets/img/post-bg.jpg',
                'image_caption' => 'Looking beyond the familiar.',
                'status' => 'published',
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'The Final Frontier',
                'slug' => 'the-final-frontier',
                'excerpt' => 'Why the pursuit of discovery continues across generations.',
                'body' => 'Aiming for the stars is a task that can occupy generations, with every discovery opening the way to another beginning.',
                'featured_image' => 'assets/img/post-sample-image.jpg',
                'image_caption' => 'Every journey begins with curiosity.',
                'status' => 'published',
                'published_at' => now()->subDay(),
            ],
            [
                'title' => 'A World Seen from Space',
                'slug' => 'a-world-seen-from-space',
                'excerpt' => 'How seeing Earth from afar can change our perspective.',
                'body' => 'From space, Earth appears fragile and shared. That view can change how we understand our responsibility to one another.',
                'featured_image' => null,
                'image_caption' => null,
                'status' => 'draft',
                'published_at' => null,
            ],
        ];

        foreach ($posts as $post) {
            Post::query()->updateOrCreate(
                ['slug' => $post['slug']],
                ['user_id' => $user->id, ...$post],
            );
        }
    }
}
