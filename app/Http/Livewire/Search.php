<?php

namespace App\Http\Livewire;

use App\Models\Article;
use Livewire\Component;

class Search extends Component
{
    public $search;

    public $results = [];

    protected $queryString = [
        'search' => ['except' => '']
    ];

    protected $listeners = ['refresh'];

    public function searchArticles()
    {
        $this->results = Article::with('purchase')
            ->where('name', 'like', '%' . \Str::lower($this->search) . '%')
            ->get()
            ->sortByDesc('purchase.date')
            ->groupBy(fn($article) => $article->purchase->date->translatedFormat('F Y'))
            ->mapWithKeys(function($articles, $date) {
                return [$date => [
                    'count' => $articles->count(),
                    'sum' => $articles->sum('price'),
                    'articles' => $articles
                    ]
                ];
            });
    } 

    public function refresh()
    {
        $this->reset('search');
    }

    public function render()
    {
        return view('livewire.search');
    }
}
