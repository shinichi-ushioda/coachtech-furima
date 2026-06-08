<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\UserController; 
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/?page=mylist', [ItemController::class, 'mylist'])->name('items.mylist');
Route::get('/items/{item_id}', [ItemController::class, 'show'])->name('items.show');// 商品詳細ページのルーティング




//FortifyServiceProvider.phpでログイン画面のルーティングを定義しているため、/loginの記述、/registerの記述、/email_verify(メール認証)の記述は不要



//認証が必要なルートをグループ化 
Route::middleware('auth', 'verified')->group(function () {
    // コメント投稿のルーティング
    Route::post('/items/{item_id}/comments', [CommentController::class, 'comment'])->name('comments.store'); 
    //いいね追加
    Route::post('/items/{item_id}/favorite', [FavoriteController::class, 'store'])->name('favorites.store');
    //いいね解除
    Route::delete('/items/{id}/favorite', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    //マイページ表示用のルーティング
    Route::get('/mypage', [ProfileController::class, 'show'])->name('mypage.show');
    
    //プロフィール編集画面を表示する（GET）
    Route::get('/mypage/profile', [ProfileController::class, 'editProfile'])->name('edit.profile');
    
    //プロフィール情報を更新する（PUT）
    Route::put('/mypage/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');

    //商品出品画面の表示 (GET)
    Route::get('/sell', [ItemController::class, 'create'])->name('item.create');
    
    //商品出品処理の実行 (POST)
    Route::post('/sell', [ItemController::class, 'store'])->name('item.store');

    //商品購入画面を表示
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])->name('items.purchase');

    //配送先住所 変更画面の表示 (GET)
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress'])->name('address.edit');
    
    //配送先住所の保存処理 (POST)
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])->name('address.update');

    //商品購入処理（StripeへリダイレクトするPOSTルートを追加）
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'checkout'])->name('purchase.checkout');
    
    //決済成功時・キャンセル時の戻り先ルート
    Route::get('/purchase/success/{item_id}', [PurchaseController::class, 'success'])->name('purchase.success');
    Route::get('/purchase/cancel/{item_id}', [PurchaseController::class, 'cancel'])->name('purchase.cancel');
});


