<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder; // 追加: クエリビルダ（検索）を使用するためのインポート 5月19日

class Item extends Model
{
    use HasFactory;

    /**
     * FN017: カテゴリリレーション
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'item_category', 'item_id', 'category_id');
    }

    /**
     * FN018: お気に入り（いいね）リレーション
     */
    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites', 'item_id', 'user_id');
    }

    /**
     * FN020: コメントリレーション
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'item_id');
    }

    /**
     * 商品の状態リレーション (Item -> Condition)
     */
    public function condition()
    {
    // itemsテーブルの condition_id カラムと conditionsテーブルの id カラムを結びつけます
        return $this->belongsTo(Condition::class); 
    }

    /**
     * この商品の注文情報を取得 (Item -> Order)
     * 1つの商品は最大1回しか注文されません（1対1）
     */
    public function order()
    {
        return $this->hasOne(Order::class, 'item_id');
    }

    /**
     * FN019: 商品の検索機能
     * 
     */
    public function scopeKeyword(Builder $query, ?string $keyword): Builder
    {
        if (!empty($keyword)) {
            return $query->where('name', 'like', "%{$keyword}%");
        }

        return $query;
    }
    // --- ここまで追加 ---


}
