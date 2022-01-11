<?php

namespace App\Http\Livewire;

use App\Models\Article;
use App\Models\Category;
use App\Models\Purchase;
use Carbon\Carbon;
use Livewire\Component;

class Chart extends Component
{
    public $selected = [
        'date' =>  '',
        'category' => 0,
        'product' => 0,
    ];

    public $articlesBySelection = [];

    public $date;

    public $details = false;

    public $total, $numOfMonths, $totalByDate;

    public $type = 'byCategory';

    public function mount()
    {
        $this->numOfMonths = Purchase::selectRaw('month(date) as month')->pluck('month')->unique()->count();
    }

    public function changeType($selectedType)
    {
        $this->reset(['selected', 'details']);

        $this->type = $selectedType;
    }

    public function updated() 
    {
        $this->reset('articlesBySelection');

        if (! $this->selected['date'] || ($this->selected['category'] == 0 && $this->selected['product'] == 0))
            return;

        $this->date = Carbon::createFromFormat('n Y', strtr($this->selected['date'], config('misc.months')));

        $query = Article::with('purchase')
            ->whereHas('purchase', function ($query) {
                $query->whereMonthAndYear($this->date);
            });
            
        $this->totalByDate = $query->sum('price');

        $this->articlesBySelection = $query
            ->filterBySelection($this->selected)
            ->get()
            ->sortByDesc(fn($article) => $article->purchase->date);

        if (! $this->details) {
            $this->articlesBySelection = $this->articlesBySelection
                ->groupBy('name')
                ->map(function($group, $name) {
                    return (object)[
                        'name' => $name,
                        'price' => $group->sum('price')
                    ];
                })
                ->sortByDesc('price');
        } 
    }

    protected function getTotal()
    {
        return Article::query()
            ->filterBySelection($this->selected)
            ->sum('price');
    }

    public function render()
    {
        $this->total = $this->getTotal();

        return view('livewire.chart', [
                'products' => config('misc.products'),
                'categories' => Category::get(),
            ])
            ->extends('layouts.master', [
                'title' => 'Chart: Einkäufe' 
            ]);
    }
}
