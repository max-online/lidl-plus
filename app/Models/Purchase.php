<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    public $guarded = [];

    protected $dates = ['date'];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function scopeWhereMonthAndYear($query, $date)
    {
        return $query->whereYear('date', Carbon::parse($date)->format('Y'))
                     ->whereMonth('date', Carbon::parse($date)->format('m'));
    }

    public function scopeFilterByDate($query, $date)
    {
        $isDate = \Str::is('****-**', $date);
        
        return $query->when($isDate, fn($query) => $query->whereMonthAndYear($date))
                     ->when($date == '', fn($query) => $query->where('date', '>=', now()->startOfMonth()->subMonthsNoOverflow()));
    }

    public function getTimeAttribute($value)
    {
        return \Str::substr($value, 0, 5);
    }

    public function getFormattedDateAttribute()
    {
        return $this->date->translatedFormat('D') . ', ' . $this->date->germanFormat();
    }

}
