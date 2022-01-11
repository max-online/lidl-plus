<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    public $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function scopeFilterBySelection($query, $selection)
    {
        return $query->when($selection['category'], fn($query, $category) => $query->where('category_id', $category))
                     ->when($selection['product'], fn($query, $product) => $query->where('name', 'like', '%' . $product . '%'));
    }
}
