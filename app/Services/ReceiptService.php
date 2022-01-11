<?php

namespace App\Services;

use App\Models\Purchase;
use App\Models\Article;
use Carbon\Carbon;
use Debugbar;

class ReceiptService
{
    public $regex = [
        'deposit' => '^Pfand(rückgabe)?\s',
        'article' => '((,|\.)\d{2}\s(A|B))$|\d,\d{2} EUR\/kg',
        'total' => '^(zu zahlen)',
        'savings' => '^(Gesamter Preisvorteil)',
        'timestamp' => '(?<code>.+)\s(?<timestamp>\d{2}\.\d{2}\.\d{2}\s*\d{2}:\d{2})',
        'split' => '\s(?=(-?\d{3,4})$)',
    ];

    public $toReplace = [
        '/(\s(A|B))$/' => '',
        '/(?<=\d).(?=\d{3})/' => ',',
        '/,(?=\d{2}$)/' => '',
    ];

    public $errors = [
        '/(?<=-)?(08|8|@)(?=,\d{2}\s(A|B))/' => 0,
        '/(?<=,)08(?=\d)/' => 0,
        '/(?<=,\d)08/' => 0,
        '/(08|8)(?=(\.|,)\d{2,3} (kg|EUR\/kg))/' => 0,
    ];

    public function parse($file)
    {
        $articleNames = collect(config('misc.articles'));

        $data = $this->readFile($file);

        $articles = $data->reject(fn($row) => $this->is('deposit', $row))
                            ->filter(fn($row) => $this->is('article', $row))
                            ->map(fn($row) => $this->cleanData($row))
                            ->map(fn($row) => $this->splitData($row));

        $bottleDeposit = $this->getSum('deposit', $data);
                                        
        $total = $this->getSum('total', $data);

        $savings = $this->getSum('savings', $data);
        
        $datestring = $data->filter(fn($row) => $this->is('timestamp', $row))
                            ->map(fn($row) => preg_replace('/(?<=:)8(?=\d)/', '0', $row))
                            ->first();

        preg_match('/' . $this->regex['timestamp'] .'/', $datestring, $matches);
    
        if (Purchase::where('code', $matches['code'])->exists()) {
            Debugbar::info('Skipping ' . $matches['code'] . ' from ' . $matches['timestamp']);
            return;
        };

        [$date, $time] = explode(' ', $matches['timestamp']);

        Debugbar::info('Importing receipt from ' . $date);

        try {
            $purchase = Purchase::create([
                'code' => $matches['code'],
                'date' => Carbon::createFromFormat('d.m.y', $date)->toDateString() . ' ' . $time,
                'time' => $time,
                'total' => $total + ($savings ?? 0),
                'savings' => $savings ?? 0,
                'bottle_deposit' => $bottleDeposit
            ]);
        } catch (\Throwable $e) {
            dd($e, 'Receipt from: ' . $date . ' ' . $time);
        }
    
        foreach($articles as $key => $article) {
            if (count($article) == 1) {
                continue;
            }

            [$rawName, $price] = $article;

            $article = Article::create([
                'purchase_id' => $purchase->id,
                'name' => $articleNames->first(fn($value, $key) => preg_match('|.*' . $key . '.*|im', $rawName)) ?? $rawName, 
                'raw_name' => $rawName,
                'price' => $price,
                'meta' => $this->addMetaData($articles, $key)
            ]);
        }
    }

    private function readFile($file)
    {
        $contents = file_get_contents($file);

        return collect(preg_split('/\n/', $contents, NULL, PREG_SPLIT_NO_EMPTY));;
    }

    protected function cleanData($row)
    {
        return preg_replace(array_keys($this->errors + $this->toReplace), array_values($this->errors + $this->toReplace), $row);
    }

    public function splitData($row)
    {
        return preg_split('/' . $this->regex['split'] . '/' ,$row);
    }

    protected function is($type, $row)
    {
        return preg_match('/' . $this->regex[$type] . '/', $row);
    }

    public function addMetaData($articles, $key)
    {
        if (! isset($articles[$key + 1]) || count($articles[$key + 1]) != 1)
            return '';

        return $articles[$key + 1][0];
    }

    protected function getSum($type, $data)
    {
        return $data->filter(fn($row) => $this->is($type, $row))
                ->map(fn($row) => $this->cleanData($row))
                ->map(fn($row) => $this->splitData($row)[1])
                ->sum();
    }
}