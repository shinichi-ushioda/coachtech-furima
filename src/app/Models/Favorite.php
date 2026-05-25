<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    // 一括画面保存を許可するカラムを指定
    protected $fillable = ['user_id', 'item_id'];
}
