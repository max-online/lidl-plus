<?php

namespace Tests\Feature;

use Livewire;
use Artisan;
use App\Models\Purchase;
use Database\Seeders\CategorySeeder;
use Database\Seeders\TagSeeder;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    public static $setUpRun = false;

    protected function setUp() :void
    {
        parent::setUp();

        if (! static::$setUpRun) {
            \Artisan::call('migrate:fresh');

            $this->seed(CategorySeeder::class);
            $this->seed(TagSeeder::class);
    
            Artisan::call('receipts:import', ['--test' => '1']);

            static::$setUpRun = true;
        }
    }

    public function test_index_page_is_shown()
    {
        $response = $this->get('/index');

        $response->assertStatus(200)
                 ->assertSee('Meine Einkäufe');
    }

    public function test_can_import_receipt()
    {
        $this->assertDatabaseCount('purchases', 2);
        $this->assertDatabaseCount('articles', 45);

        $this->assertEquals(17, Purchase::first()->articles()->count());
    }

    public function test_can_show_single_purchase()
    {
        $response = $this->get('/purchase/' . Purchase::first()->id);

        $response->assertSee('Einkauf vom Sa., 17.04.21 - 12:14 Uhr')
                 ->assertSee('22,88 €')
                 ->assertStatus(200);

        Livewire::test('shopping-details', [Purchase::first()->id])
            ->set('selectedCategory', 6)
            ->assertSee('Buttermilch')
            ->assertSee('Schlagsahne')
            ->assertSee('1,57');
    }

    public function test_can_search_for_articles()
    {
        $this->assertDatabaseCount('articles', 45);

        Livewire::test('search')
            ->set('search', 'Apfel')
            ->call('searchArticles')
            ->assertSee('3 Artikel gefunden')
            ->assertSee('Naturradler Apfel')
            ->assertSee('Apfel');
    }
}
