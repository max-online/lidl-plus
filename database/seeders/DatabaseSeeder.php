<?php

namespace Database\Seeders;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CategorySeeder::class,
            TagSeeder::class
        ]);
    }
}
