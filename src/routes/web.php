<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HelloController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController; 

Route::get('/', [ItemController::class, 'index']);
Route::get('/show', [ItemController::class, 'mylist']);

//FortifyServiceProvider.phpでログイン画面のルーティングを定義しているため、/loginの記述は不要

// 認証が必要なルートをグループ化 dashboardが不要なら消す
Route::middleware('auth')->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});