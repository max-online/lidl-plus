<?php

namespace App\Charts;

use App\Models\Article;
use Carbon\Carbon;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;
use Str;

class ByCategory extends BaseChart
{
    
    public function handler(Request $request): Chartisan
    {
        $articles = Article::with('purchase')->get()
            ->groupBy(fn($article) => $article->purchase->date->format('Y-m'))
            ->sortBy(fn ($object, $key) => $key)
            ->when(request('category') != 0, function($collection) {
                return $collection->map(function($articles) {
                    return $articles->filter(fn($article) => $article->category_id == request('category'));
                });
            })
            ->when(request('product'), function($collection) {
                return $collection->map(function($articles) {
                    return $articles->filter(fn($article) => preg_match('/' . request('product') . '/i', $article->name));
                });
            });

        $labels = $articles->keys()
            ->map(fn($date) => Carbon::createFromFormat('!Y-m', $date)->translatedFormat('M Y'))
            ->toArray();

        $total = $articles->map(fn($collection) => $collection->sum('price')/100)
                    ->values()
                    ->toArray();

        $average = array_fill(0, count($labels), array_sum($total)/count($labels));

        return Chartisan::build()
                ->labels($labels)
                ->dataset('Durchschnitt', $average)
                ->dataset('Ausgaben', $total);
    }
}