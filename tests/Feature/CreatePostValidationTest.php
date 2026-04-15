<?php

namespace Tests\Feature;

use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class CreatePostValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Filament pages expect a current panel.
        Filament::setCurrentPanel('admin');
        Filament::bootCurrentPanel();

        // Allow access for non-FilamentUser models during tests.
        config(['app.env' => 'local']);

        Storage::fake('public');
    }

    public function test_it_validates_required_and_min_length_rules(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(CreatePost::class)
            ->set('data.title', 'abcd') // < 5
            ->set('data.slug', 'ab') // < 3
            ->set('data.category_id', null) // required
            ->set('data.image', null) // required
            ->call('create')
            ->assertHasErrors([
                'data.title' => 'min',
                'data.slug' => 'min',
                'data.category_id' => 'required',
                'data.image' => 'required',
            ]);
    }

    public function test_it_validates_slug_must_be_unique(): void
    {
        $user = User::factory()->create();

        $category = Category::query()->create([
            'name' => 'Category Test',
            'slug' => 'category-test',
        ]);

        Post::query()->create([
            'title' => 'Existing Post',
            'slug' => 'existing-slug',
            'category_id' => $category->id,
        ]);

        Livewire::actingAs($user)
            ->test(CreatePost::class)
            ->set('data.title', 'Valid Title')
            ->set('data.slug', 'existing-slug')
            ->set('data.category_id', $category->id)
            ->set('data.image', UploadedFile::fake()->image('post.jpg'))
            ->call('create')
            ->assertHasErrors([
                'data.slug' => 'unique',
            ]);
    }
}
