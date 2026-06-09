<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Item extends Model
{
    use HasFactory;

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'item_category', 'item_id', 'category_id');
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites', 'item_id', 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'item_id');
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class); 
    }

    public function order()
    {
        return $this->hasOne(Order::class, 'item_id');
    }

    public function scopeKeyword(Builder $query, ?string $keyword): Builder
    {
        if (!empty($keyword)) {
            return $query->where('name', 'like', "%{$keyword}%");
        }

        return $query;
    }
    
    protected $fillable = [
        'name',
        'price',
        'brand_name',
        'description',
        'img_url',
        'condition_id',
        'user_id',
        'is_sold',
    ];


}
