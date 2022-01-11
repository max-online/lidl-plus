<?php

namespace App\Http\Livewire;

use App\Models\Article;
use App\Models\Category;
use Livewire\Component;

class Toplist extends Component
{
    public $selectedCategory;

    public $limit = 5;

    public $categories;

    public $mode = 'price';

    public function mount()
    {
        $this->categories = Category::get();
    }

    public function modifyLimit()
    {
        $this->limit = match($this->limit) {
            5 => 10,
            10 => 5,
            default => 5
        };
    }

    public function toggleMode()
    {
        $this->mode = ($this->mode == 'price' ? 'count' : 'price');
    }

    public function updating()
    {
        usleep(600000);
    }

    public function render()
    {
        if ($this->selectedCategory) {
            $articles = Article::with('purchase')
                ->where('category_id', $this->selectedCategory)
                ->get()
                ->groupBy(fn($item) => $item->purchase->date->format('Y-m'))
                ->sortKeysDesc()
                ->mapWithKeys(function($collection, $date) {
                    $collection = $collection->groupBy('name')
                        ->map(fn($collection) => $collection->sum('price'))
                        ->sortDesc()
                        ->take($this->limit);

                    return [$date => $collection];
                });
        }

        $topArticles = Article::get()
            ->groupBy('name')
            ->when($this->mode == 'price', function($collection) {
                return $collection->mapWithKeys(function($group, $name) {
                    return [$name => [
                        'total' => $group->sum('price'),
                        'count' => $group->count(),
                        ]
                    ];
                });
            })
            ->when($this->mode == 'count', function($collection) {
                return $collection->mapWithKeys(fn($group, $name) => [$name => ['count' => $group->count()]]);
            })
            ->sortDesc()
            ->take(30);

        return view('livewire.toplist', [
            'topArticles' => $topArticles,
            'articlesByMonth' => $articles ?? null,
        ])->extends('layouts.master', [
            'title' => 'Einkäufe - Top 10' 
        ]);
    }
}
