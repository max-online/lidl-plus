<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Purchase;
use App\Models\Category;
use App\Models\Article;

class Dashboard extends Component
{
    public $dates;

    public $date = '';
    public $selectedCategory = '';

    public $details = false;

    protected $queryString = [
        'date' => ['except' => ''],
        'selectedCategory' => ['except' => ''],
    ];

    public function mount()
    {
        $this->dates = Purchase::select('date')
            ->latest('date')
            ->pluck('date')
            ->unique()
            ->mapWithKeys(fn($date) => [$date->format('Y-m') => $date->translatedFormat('F Y')]);
    }

    public function updating()
    {
        usleep(600000);
    }

    public function updatingDate()
    {
        $this->reset('selectedCategory', 'details');
    }

    public function refresh()
    {
        $this->reset(['date', 'selectedCategory']);

        $this->emit('refresh');
    }

    public function render()
    {
        $isDate = \Str::is('****-**', $this->date);

        $purchases = Purchase::query()
            ->filterByDate($this->date)
            ->latest('date')
            ->get();

        if ($isDate) {
            $byCategory = Article::query()
                ->selectRaw('category_id, sum(price) as sum')
                ->whereHas('purchase', function($query) {
                    $query->whereMonthAndYear($this->date);
                })
                ->groupBy('category_id')
                ->orderBy('sum', 'desc')
                ->pluck('sum', 'category_id');
        }

        if ($isDate && $this->selectedCategory) {
            $articles = Article::with('purchase')
                ->whereHas('purchase', function($query) {
                    $query->whereMonthAndYear($this->date);
                })
                ->where('category_id', $this->selectedCategory)
                ->orderBy('price', 'desc')
                ->get();

            if (! $this->details) {
                $articles = $articles->groupBy('name')
                    ->map(function($group) {
                        return (object)[
                            'name' => $group->first()['name'],
                            'price' => $group->sum('price')
                        ];
                    })
                    ->sortByDesc('price');
            }
        }

        return view('livewire.dashboard', [
            'purchases' => $purchases,
            'categories' => Category::get(),
            'byCategory' => $byCategory ?? [],
            'articles' => $articles ?? []
        ])->extends('layouts.master', [
            'title' => 'Meine Einkäufe' 
        ]);
    }
}
