<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Purchase;
use Livewire\Component;

class Timeline extends Component
{
    public $selectedDate, $selectedCategory;

    public $categories, $dates, $purchases;

    public function mount()
    {
        $this->purchases = collect();

        $this->categories = Category::get();

        $this->dates = Purchase::select('date')
            ->orderBy('date', 'desc')
            ->pluck('date')
            ->mapWithKeys(function($date) {
                return [$date->format('Y-m') => $date->translatedFormat('F Y')];
            });
    }

    public function updated()
    {
        if (! $this->selectedCategory || ! $this->selectedDate) {
            $this->purchases = collect();
            return;
        } 

        usleep(600000);

        $this->purchases = Purchase::with(['articles' => function($query) {
                $query->where('category_id', $this->selectedCategory)->orderBy('name');
            }])
            ->whereMonthAndYear($this->selectedDate)
            ->orderBy('date')
            ->get();
    }

    public function render()
    {
        return view('livewire.timeline')
            ->extends('layouts.master', [
                'title' => 'Timeline: Einkäufe' 
            ]);
    }
}
