<?php

namespace Tests\Feature;

use App\Http\Livewire\TagsList;
use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_new_tag()
    {
        $this->seed(CategorySeeder::class);

        Livewire::test(TagsList::class)
            ->set('selectedCategory', Category::first()->id)
            ->set('name', 'Obstsalat')
            ->call('saveTag');

        $this->assertDatabaseHas('tags', [
            'name' => 'Obstsalat',
            'category_id' => Category::first()->id
        ]);
    }

    public function test_name_field_is_required()
    {
        $this->seed(CategorySeeder::class);

        Livewire::test(TagsList::class)
            ->set('selectedCategory', Category::first()->id)
            ->set('name', '')
            ->call('saveTag')
            ->assertHasErrors(['name' => 'required']);

        $this->assertDatabaseMissing('tags', [
            'name' => 'Obstsalat',
            'category_id' => Category::first()->id
        ]);
    }
}
