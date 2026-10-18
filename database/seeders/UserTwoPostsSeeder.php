<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserTwoPostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->find(2);

        if (! $user) {
            $this->command?->warn('User dengan ID 2 tidak ditemukan. Artikel dummy tidak dibuat.');

            return;
        }

        $category = Category::query()->firstOrCreate(
            ['slug' => 'catatan-perjalanan'],
            ['name' => 'Catatan Perjalanan'],
        );

        $tagNames = ['perjalanan', 'inspirasi', 'catatan'];
        $tags = collect($tagNames)->map(fn (string $name): Tag => Tag::query()->firstOrCreate(
            ['slug' => Str::slug($name)],
            ['name' => Str::title($name)],
        ));

        $posts = [
            [
                'title' => 'Pagi yang Berbeda di Ubud',
                'slug' => 'pagi-yang-berbeda-di-ubud',
                'excerpt' => 'Menemukan ketenangan dalam langkah kecil menyusuri Ubud saat matahari baru terbit.',
                'body' => '<p>Ubud terasa berbeda ketika jalanan masih lengang. Udara pagi, suara alam, dan secangkir kopi memberi ruang untuk memulai hari dengan lebih pelan.</p><p>Perjalanan singkat ini mengingatkan saya bahwa kadang yang kita butuhkan hanyalah berhenti sejenak dan memperhatikan sekitar.</p>',
                'featured_image' => 'assets/img/post-bg.jpg',
                'image_caption' => 'Suasana pagi yang tenang.',
                'status' => 'published',
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Belajar dari Perjalanan Kereta',
                'slug' => 'belajar-dari-perjalanan-kereta',
                'excerpt' => 'Tentang orang-orang yang ditemui, pemandangan yang lewat, dan cerita yang ikut pulang.',
                'body' => '<p>Di dalam kereta, setiap orang membawa tujuan dan ceritanya sendiri. Kita berbagi ruang untuk beberapa jam, lalu kembali melanjutkan arah masing-masing.</p><p>Pemandangan yang berubah di balik jendela menjadi pengingat bahwa perjalanan bukan hanya soal tiba, tetapi juga tentang hal-hal yang kita temui di sepanjang jalan.</p>',
                'featured_image' => 'assets/img/post-sample-image.jpg',
                'image_caption' => 'Pemandangan dalam perjalanan.',
                'status' => 'published',
                'published_at' => now()->subDay(),
            ],
            [
                'title' => 'Rencana Menjelajah Kota Lama',
                'slug' => 'rencana-menjelajah-kota-lama',
                'excerpt' => 'Draft rencana perjalanan untuk mengenal sisi lain kota lewat bangunan dan kisah lamanya.',
                'body' => '<p>Saya ingin berjalan tanpa terburu-buru, memperhatikan detail bangunan, dan mengumpulkan cerita dari tempat-tempat yang sering kita lewati.</p>',
                'featured_image' => null,
                'image_caption' => null,
                'status' => 'draft',
                'published_at' => null,
            ],
        ];

        foreach ($posts as $postData) {
            $post = Post::query()->updateOrCreate(
                ['slug' => $postData['slug']],
                [
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                    ...$postData,
                ],
            );

            $post->tags()->sync($tags->pluck('id'));
        }
    }
}
