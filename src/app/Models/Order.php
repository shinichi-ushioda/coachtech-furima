<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * この注文を行ったユーザーを取得 (Order -> User)
     */
    public function user()
    {
    return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * この注文で売れた商品を取得 (Order -> Item)
     */
    public function item()
    {
    return $this->belongsTo(Item::class, 'item_id');
    }
}
