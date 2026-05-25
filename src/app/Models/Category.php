<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * 複数代入（Mass Assignment）を許可するカラムの定義
     */
    protected $fillable = [
        'name',
    ];

    /**
     * このカテゴリに属する商品の一覧を取得 (Category -> Items)
     * 商品とカテゴリは多対多の関係（belongsToMany）になります
     */
    public function items()
    {
        // 第2引数には中間テーブル名（例: 'category_item'）を指定します
        return $this->belongsToMany(Item::class, 'item_category', 'category_id', 'item_id');
    }
}


