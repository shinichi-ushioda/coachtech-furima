<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // これが with('comments.user') の「.user」で呼ばれるメソッド名
    public function user() {
        return $this->belongsTo(User::class); // 内部で自動的に comments テーブルの user_id カラムを探す
    }

    /**
     * このコメントが投稿された商品を取得 (Comment -> Item)
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
