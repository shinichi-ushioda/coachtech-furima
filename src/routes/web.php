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
Route::get('/items/{item_id}', [ItemController::class, 'show'])->name('items.show');

//FortifyServiceProvider.phpでログイン画面のルーティングを定義しているため、/loginの記述、/registerの記述、/email_verify(メール認証)の記述は不要

//認証必要ルート
Route::middleware('auth', 'verified')->group(function () {

    Route::post('/items/{item_id}/comments', [CommentController::class, 'comment'])->name('comments.store'); 

    Route::post('/items/{item_id}/favorite', [FavoriteController::class, 'store'])->name('favorites.store');

    Route::delete('/items/{id}/favorite', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

    Route::get('/mypage', [ProfileController::class, 'show'])->name('mypage.show');
    
    Route::get('/mypage/profile', [ProfileController::class, 'editProfile'])->name('edit.profile');
    
    Route::put('/mypage/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');

    Route::get('/sell', [ItemController::class, 'create'])->name('item.create');
    
    Route::post('/sell', [ItemController::class, 'store'])->name('item.store');

    Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])->name('items.purchase');

    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress'])->name('address.edit');
    
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])->name('address.update');

    Route::post('/purchase/{item_id}', [PurchaseController::class, 'checkout'])->name('purchase.checkout');
    
    Route::get('/purchase/success/{item_id}', [PurchaseController::class, 'success'])->name('purchase.success');

    Route::get('/purchase/cancel/{item_id}', [PurchaseController::class, 'cancel'])->name('purchase.cancel');
});


