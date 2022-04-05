<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $articles = [
            // Obst
            1 => [
                'orangen',
                'mango',
                'äpfel',
                'apfel',
                'banane',
                'kiwi',
                'heidelbeere',
                'beeren',
                'mandarine',
                'himbeere',
                'aprikose',
                'nektarine',
                'ananas',
                'erdbeere',
                'kirsche',
                'physalis',
                'clementine',
                'pflaume',
                'birnen',
                'mirabellen',
                'zwetschgen',
                'trauben',
                'feige',
                'satsumas',
                'honigpomelo',
            ],
            // Gemüse
            2 => [
                'möhre',
                'karotte',
                'rucola',
                'gurke',
                'spargel',
                'knoblauch',
                'champign',
                'zwiebeln',
                'paprika',
                'spinat',
                'zucchini',
                'auberginen',
                'kaisergem',
                'kartoffel',
                'salat',
                'broccoli',
                'brokkoli',
                'gemüse',
                'oliven',
                'gurken',
                'tomaten',
                'suppengrün',
                'romana',
                'blumenkohl',
                'bohnen',
                'hokkaido',
                'cornichons',
                'rosenkohl',
                'kürbis',
            ],
            // Getränke
            3 => [
                'wasser',
                'smoothie',
                'kaffee',
                'radler',
                'naturradler',
                'kamillentee'
            ],
            // Fleisch
            4 => [
                'fleisch',
                'salami',
                'puten',
                'truthahn',
                'hähnchen',
                'cevapcici',
                'hähn.-platte',
                'schinken',
                'chorizo',
                'rind',
                'würstchen',
                'hähn.-geschnetzeltes',
                'wiener',
                'prosciutto',
                'leberw',
                'bockwurst',
                'teewurst',
                'aufschnitt',
                'paprikalyoner',
                'fleischsalat',
            ],
            // Fisch
            5 => [
                'lachs',
                'thunfisch',
                'hering',
                'msc',
                'matjes',
            ],
            // Milchprodukte
            6 => [
                'milch',
                'biol.natur',
                'creme fraiche',
                'quark',
                'kerrygold gesalzen',
                'joghurt',
                'jogh.',
                'kefir',
                'sahne',
                'butter',
                'milchdrink erdbeere'
            ],
            // Käse
            7 => [
                'käse',
                'emmentaler',
                'frischk',
                'crefee',
                'bergader',
                'gouda',
                'mozzarella',
                'feta',
                'philadelphia kräuter',
                'käseaufschnitt',
            ],
            // Haushalt
            8 => [
                'tücher',
                'tuch',
                'müllbeutel',
                'tasche',
                'müll',
                'toiletten',
                'waschmittel',
                'backpapier',
                'geschirr tabs',
                'wc-bürste',
            ],
            // Gewürze
            9 => [
                'thymian',
                'rosmarin',
                'salz',
                'pfeffer',
                'rosenpaprika',
                'gewürz',
                'koriander',
                'sonnenblumenöl',
                'rapsöl',
                'petersilie',
                'pesto',
                'balsamico di modena',
                'zimt gemahlen',
                'basilikum',
                'nudel schinken',
            ],
            // Getreideprodukte
            10 => [
                'hafer',
                'mehl',
                'brot',
                'brötch',
                'roggenkruste',
                'wraps',
                'spaghetti',
                'penne',
                'couscous',
                'bulgur',
                'reis',
                'eierspätzle',
                'fusilli',
                'blätterteig',
                'tortelloni',
                'nudeln',
                'burger',
                'maultaschen',
                'rosenbröt',
                'weltmeisterbr',
                'crustini',
                'gnocchi',
                'bauernkruste',
                'toast',
            ],
            // Eier
            11 => [
                'eier'
            ],
            // Süßes u. Snacks
            12 => [
                'praline',
                'pudding',
                'nuss',
                'windbeutel',
                'nuts',
                'müller milchr',
                'cacao',
                'salzbrezeln',
                'sultaninen',
                'cashew',
                'futter classic',
                'chocochips',
                'eiskonfekt',
                'cremissimo',
                'cremissi',
                'chips',
                'waffelwürfel',
                'stapelchips',
                'joghurt & kirschen',
                'katjes',
                'pring',
                'milkshake',
                'grandioso choc',
                'müller milch vanille',
                'studentenfutter',
                'schokolade',
                'weihnachtsmann',
                'backpulver',
            ],
            // Fertiggerichte
            13 => [
                'frikassee',
                'knusperfr',
                'uncleb',
                'frühlingsroll',
                'hähnchenroll',
                'pizzateig',
                'pizza',
                'pommes',
                'sweet & sour sauce',
                'Picco Pizzi',
                'bensor',
                'dr. oetk rist. diav',
                'linseneintopf',
                'sushi',
            ],

            14 => [
                'zahncreme',
                'anti schup',
                'deo',
                'cremeseife',
                'shampoo',
            ],
            15 => [
                'honig',
                'hummus',
                'lätta',
            ],
        ];

        $data = [];

        foreach ($articles as $category => $group) {
            foreach ($group as $tag) {
                $data[] = [
                    'category_id' => $category,
                    'name' => $tag
                ];
            }
        }

        \DB::table('tags')->insert($data);
    }
}
