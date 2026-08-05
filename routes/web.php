<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\RouhiController;
use App\Http\Controllers\BonusController;


Route::get('/', function () {

    return redirect('/login');

});
// ログイン画面
Route::get('/login', [LoginController::class, 'index'])
->name('login.index');
// ログイン処理
Route::post('/login', [LoginController::class, 'login'])
->name('login.login');

Route::middleware('auth')
->group(function () {
    // ダッシュボード画面
    Route::get('/dashboard', function () {
        return view('login.dashboard');
    })->name('dashboard');
    // ログアウト処理
    Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

    Route::get('/shift-input', function () {
        return view('login.shift-input');
    })->name('shift-input');
    Route::get('/shift-edit', function () {
        return view('login.shift-edit');
    })->name('shift-edit');

    // それぞれシフトボーナス浪費計算処理
    Route::post('/shift-input', [ShiftController::class, 'store'])
    ->name('shift.store');
    Route::post('/expenses-input',[RouhiController::class, 'store'])
    ->name('expenses.store');
    Route::post('/bonus-input',[BonusController::class, 'store'])
    ->name('bonus.store');

    Route::resource('shifts', ShiftController::class)
    ->only(['index', 'edit', 'update', 'destroy']);
    Route::resource('expenses', RouhiController::class)
    ->only(['index', 'edit', 'update', 'destroy']);
    Route::resource('bonuses', BonusController::class)
    ->only(['index', 'edit', 'update', 'destroy']);
});