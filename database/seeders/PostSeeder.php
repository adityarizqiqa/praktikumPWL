<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryIdsBySlug = Category::query()->pluck('id', 'slug');

        $posts = [
            [
                'title' => 'Belajar Laravel Dasar',
                'slug' => 'belajar-laravel-dasar',
                'category_id' => $categoryIdsBySlug['laravel'] ?? null,
                'body' => 'Materi dasar Laravel untuk praktikum PWL.',
                'tags' => ['laravel', 'pwl'],
                'published' => true,
                'published_at' => now()->toDateString(),
            ],
            [
                'title' => 'Mengenal Filament Admin',
                'slug' => 'mengenal-filament-admin',
                'category_id' => $categoryIdsBySlug['filament'] ?? null,
                'body' => 'Contoh CRUD sederhana menggunakan Filament.',
                'tags' => ['filament', 'admin'],
                'published' => false,
                'published_at' => null,
            ],
            [
                'title' => 'Tips PHP untuk Backend',
                'slug' => 'tips-php-untuk-backend',
                'category_id' => $categoryIdsBySlug['php'] ?? null,
                'body' => 'Beberapa tips ringkas untuk menulis PHP yang rapi.',
                'tags' => ['php', 'backend'],
                'published' => true,
                'published_at' => now()->toDateString(),
            ],
        ];

        foreach ($posts as $post) {
            if ($post['category_id'] === null) {
                continue;
            }

            Post::query()->updateOrCreate(
                ['slug' => $post['slug']],
                $post,
            );
        }
    }
}
