<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Purchase;
use App\Models\Category;

class PurchaseDetails extends Component
{
    public $selectedCategory;

    public $categories;

    public $purchase;

    public $purchases = [
        'next' => '',
        'prev' => '',
    ];

    public $date;

    protected $queryString = [
        'selectedCategory' => ['except' => '']
    ];

    public function mount(Purchase $purchase)
    {
        $this->purchase = $purchase;

        $this->categories = Category::whereIn('id', $this->purchase->articles()->pluck('category_id')->unique())
                        ->orderBy('id')
                        ->get();

        $this->purchases['next'] = Purchase::select('id', 'date')->where('date', '>', $this->purchase->date)->oldest('date')->first();
        $this->purchases['prev'] = Purchase::select('id', 'date')->where('date', '<', $this->purchase->date)->latest('date')->first();
    }

    public function updating()
    {
        usleep(600000);
    }

    public function render()
    {
        $articles = $this->purchase->articles()
            ->with('category')
            ->when($this->selectedCategory, fn($query, $value) => $query->where('category_id', $value))
            ->orderBy('category_id')
            ->orderBy('price', 'desc')
            ->get()
            ->map(function($article) use (&$category) {
                $article['category_name'] = ($category != $article->category->name ? $article->category->name : '');
                $category = $article->category->name;

                return $article;
            });

        $byCategory = $this->purchase->articles()
            ->with('category')
            ->selectRaw('category_id, SUM(price) as sum')
            ->groupBy('category_id')
            ->orderBy('sum', 'desc')
            ->pluck('sum', 'category_id');

        return view('livewire.purchase-details', [
            'articles' => $articles,
            'byCategory' => $byCategory,
        ])->extends('layouts.master', [
            'title' => 'Einkauf vom ' . $this->purchase->date->format('d.m') 
        ]);
    }
}
