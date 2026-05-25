<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    use HasFactory;
    /**
     * 複数代入（Mass Assignment）を許可するカラムの定義
     */
    protected $fillable = [
        'name',
    ];
    /**
     * この状態に該当する商品の一覧を取得 (Condition -> Items)
     * 1つの商品の状態（例: 良好）には、複数の商品が当てはまります（1対多）
     */
    public function items()
    {
        // 第2引数には itemsテーブルにある状態IDのカラム名（'condition_id'）を指定します
        return $this->hasMany(Item::class, 'condition_id');
    }
}
