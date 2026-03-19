<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::upsert(
            [
                ['name' => 'Laravel', 'slug' => 'laravel'],
                ['name' => 'Filament', 'slug' => 'filament'],
                ['name' => 'PHP', 'slug' => 'php'],
            ],
            ['slug'],
            ['name'],
        );
    }
}
