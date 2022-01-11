<?php

namespace App\Http\Livewire;

use App\Models\Article;
use App\Models\Category;
use Livewire\Component;

class ShoppingList extends Component
{
    public $topArticles, $categories, $selectedCategories, $newArticle, $checkedArticles;

    public function mount()
    {      
        $this->selectedCategories = ['Obst', 'Gemüse', 'Fleisch', 'Milchprodukte', 'Käse'];

        $this->categories = Category::get();

        $articles = Article::with('category')
            ->whereRelation('purchase', 'date', '>', now()->subMonths(2))
            ->get()
            ->groupBy('name')
            ->mapWithKeys(function($group, $name) {
                return [$name => [
                        'name' => $name,
                        'count' => $group->count(),
                        'category' => in_array($group[0]->category->name, $this->selectedCategories) ? $group[0]->category->name : 'Sonstiges'
                    ]
                ];
            });

        $this->topArticles = $articles->reject(fn($article) => $article['count'] < 4)
            ->sortByDesc('count');

        $this->checkedArticles = $this->topArticles->map(fn($article) => $article['count'] > 5 ? true : false);

        $this->selectedCategories[] = 'Sonstiges';
    }
    
    public function addItem($category)
    {
        if (! isset($this->newArticle[$category]) || strlen($this->newArticle[$category]) < 3) {
            $this->addError('category.' . $category, 'Artikel eingeben.');
            return;
        }

        if ($this->checkedArticles->keys()->contains($this->newArticle[$category])) {
            $this->addError('error' . $category, 'Artikel steht schon auf der Liste.');
            return;
        }

        $this->topArticles = $this->topArticles->merge([$this->newArticle[$category] => [
                'name' => $this->newArticle[$category],
                'count' => 5,
                'category' => $category
            ]
        ]);

        $this->checkedArticles = $this->checkedArticles->merge([$this->newArticle[$category] => true]);

        $this->newArticle[$category] = '';
    }
    
    public function removeItem($articleName)
    {
        $this->topArticles->forget($articleName);

        $this->checkedArticles->forget($articleName);
    }

    public function export()
    {
        $pdf = \App::make('dompdf.wrapper');
        $pdf = $pdf->loadView('pdf.shopping-list', [
            'selectedCategories' => collect($this->selectedCategories),
            'articles' => $this->topArticles->whereIn('name', $this->checkedArticles->filter()->keys())
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'test.pdf');
    }

    public function render()
    {
        // return view('pdf.shopping-list', [
        //     'selectedCategories' => collect(['Obst', 'Gemüse', 'Fleisch', 'Milchprodukte', 'Käse']),
        //     'articles' => $this->topArticles->whereIn('name', $this->checkedArticles->filter()->keys())
        // ])->extends('layouts.master', [
        //             'title' => 'Einkaufsliste' 
        //         ]);

        return view('livewire.shopping-list')
            ->extends('layouts.master', [
                'title' => 'Einkaufsliste' 
            ]);
    }
}
