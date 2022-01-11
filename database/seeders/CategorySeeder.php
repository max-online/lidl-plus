<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('categories')->insert([
            [
                'name' => 'Obst',
            ],[
                'name' => 'Gemüse',
            ],[
                'name' => 'Getränke',
            ],[
                'name' => 'Fleisch',
            ],[
                'name' => 'Fisch',
            ],[
                'name' => 'Milchprodukte',
            ],[
                'name' => 'Käse',
            ],[
                'name' => 'Haushalt',
            ],[
                'name' => 'Gewürze u. Öl',
            ],[
                'name' => 'Getreideprodukte'
            ],[
                'name' => 'Eier'
            ],[
                'name' => 'Süßes u. Snacks'
            ],[
                'name' => 'Fertiggerichte'
            ],[
                'name' => 'Hygiene'
            ],[
                'name' => 'Aufstrich'
            ]
        ]);

        \DB::table('categories')->insert(['id' => 99, 'name' => 'Sonstiges' ]);
    }
}
