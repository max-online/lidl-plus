<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Tag;

class CategoryService
{
    const CATEGORY_OTHERS = 99;

    public function categorize()
    {
        $articles = Article::distinct()->get(['name']);

        $tags = Tag::get()
            ->mapWithKeys(fn($item) => [$item['name'] => $item['category_id']]);

        foreach($articles as $article) {
            $filteredTags = $tags->filter(fn($category, $tag) => preg_match('/' . $tag . '/i', $article->name));

            $categoryId = $this->determineCategory($article, $filteredTags);

            Article::where('name', $article->name)->update([
                'category_id' => $categoryId
            ]);

        };
    }

    protected function determineCategory($article, $filteredTags)
    {
        $matches = collect();

        foreach ($filteredTags as $tag => $category) {
            $matches->push([
                'category' => $category,
                'similarity' => $this->calculateSimilarity($article->name, $tag),
            ]);
        }

        if ($matches->isEmpty()) {
            return self::CATEGORY_OTHERS;
        }

        return $matches->groupBy('category')
            ->map(fn($row) => $row->sum('similarity'))
            ->sortDesc()
            ->keys()
            ->first();
    }

    protected function calculateSimilarity($article, $tag)
    {
        $score = similar_text(\Str::lower($article), $tag);

        if (str_ends_with(\Str::lower($article), $tag))
            $score += 3;

        return $score;
    }
}
