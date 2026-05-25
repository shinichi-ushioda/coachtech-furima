<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'postal_code', //5月22日追加　会員登録画面の最初は郵便番号登録欄がないため
        'address', //5月22日追加　会員登録画面の最初は住所登録欄がないため
        'building',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

        /* =========================================================================
     *  ここから下が、中間テーブルの設計に合わせた正しいリレーションです
     * ========================================================================= */

    /**
     * 1. 自分が「お気に入り登録（いいね）した」商品の一覧 (User -> favorites -> Items)
     * usersテーブルとitemsテーブルは直接繋がっておらず、favoritesテーブルを中間テーブルとした多対多の関係です
     */
    public function favorites()
    {
        // belongsToMany(相手のモデル名, 中間テーブル名, 自分のIDカラム, 相手のIDカラム)
        return $this->belongsToMany(Item::class, 'favorites', 'user_id', 'item_id');
    }

    /**
     * 2. 自分がこれまでに「注文（購入）した」履歴の一覧 (User -> Orders)
     * 1人のユーザーは複数の注文を出すことができます（1対多）
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    /**
     * 3. 自分が「投稿した」コメントの一覧 (User -> Comments)
     * 1人のユーザーは複数のコメントを投稿できます（1対多）
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }
}