<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Article;
use App\Models\Category;
use Carbon\Carbon;

class Statistics extends Component
{
    public $showModal, $showPercentages;

    public $selection;

    public $dates, $month;

    public $categories;

    public $categoryName;

    protected $listeners = ['showDetails'];

    public function mount()
    {
        $this->categories = Category::get();
    }

    public function showDetails($date, $categoryId)
    {
        $this->month = [
            'key' => $date,
            'name' => $this->dates[$date]
        ];

        $this->categoryName = $this->categories->firstWhere('id', $categoryId)->name;

        $this->selection = Article::whereHas('purchase', function($query) use ($date) {
                $query->whereMonthAndYear($date);
            })
            ->where('category_id', $categoryId)
            ->get()
            ->groupBy('name')
            ->map(function($group, $name) {
                return (object)[
                    'name' => $name,
                    'price' => $group->sum('price')
                ];
            })->sortByDesc('price');

        $this->showModal = true;
    }

    public function render()
    {
        $articlesByMonth = Article::with('purchase')->get()
                    ->groupBy([fn($article) => $article->purchase->date->format('Y-m'), 'category_id'])
                    ->sortKeys();

        $totals = [];

        foreach ($articlesByMonth as $articlesByCategory) {
            
            foreach ($articlesByCategory as $category => $articles) {
                $totals[$category] = $articles->sum('price') + ($totals[$category] ?? 0);
            }

        }

        $this->dates = $articlesByMonth->keys()
            ->pop(6)
            ->sort()
            ->mapWithKeys(function($date) {
                return [$date => Carbon::createFromFormat('!Y-m', $date)->translatedFormat('M\'y')];
            });
    
        return view('livewire.statistics', [
            'articles' => $articlesByMonth,
            'totals' => $totals,
            'numMonths' => $articlesByMonth->count(),
        ])->extends('layouts.master', [
            'title' => 'Statistik: Einkäufe' 
        ]);
    }
}
