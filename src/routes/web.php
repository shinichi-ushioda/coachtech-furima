<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;

Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/?tab=mylist', [ItemController::class, 'mylist'])->name('items.mylist');
Route::get('/items/{id}', [ItemController::class, 'show'])->name('items.show');// 商品詳細ページのルーティング

Route::post('/items/{id}/comments', [CommentController::class, 'storeComment'])->name('comments.store'); // コメント投稿のルーティング


// 🌟 【FN019用】商品購入画面を表示するルーティング（ログインユーザーのみ）
Route::get('/items/{id}/purchase', [ItemController::class, 'purchase'])
    ->middleware('auth')
    ->name('items.purchase'); // 👈 これでエラーが解消されます！


//FortifyServiceProvider.phpでログイン画面のルーティングを定義しているため、/loginの記述は不要


//認証が必要なルートをグループ化 
Route::middleware('auth')->group(function () {
    // いいね追加
    Route::post('/items/{id}/favorite', [FavoriteController::class, 'store'])->name('favorites.store');
    // いいね解除
    Route::delete('/items/{id}/favorite', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    // マイページ表示用のルーティング
    Route::get('/mypage', [ProfileController::class, 'profile'])->name('mypage.show');
    
    // 💡 プロフィール編集画面や更新処理を今後追加する場合は、ここに続けて書いていきます
});
