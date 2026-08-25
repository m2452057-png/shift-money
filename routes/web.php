<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\RouhiController;
use App\Http\Controllers\BonusController;




use App\Http\Controllers\SavingGoalController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {

    return redirect('/login');

})->name('login');
// ログイン画面
Route::get('/login', [LoginController::class, 'index'])
->name('login.index');
// ログイン処理
Route::post('/login', [LoginController::class, 'login'])
->name('login.login');

Route::middleware('auth')
->group(function () {
    // ダッシュボード画面
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])
    ->name('dashboard');
    // ログアウト処理
    Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

    Route::get('/shift-input', function () {
        return view('login.shift-input');
    })->name('shift-input');


    // それぞれシフトボーナス浪費計算処理
    Route::post('/shift-input', [ShiftController::class, 'store'])
    ->name('shift.store');
    Route::post('/expenses-input',[RouhiController::class, 'store'])
    ->name('expenses.store');
    Route::post('/bonus-input',[BonusController::class, 'store'])
    ->name('bonus.store');

    // 一覧・編集・更新・削除
    Route::resource('shifts', ShiftController::class)
    ->only(['index', 'edit', 'update', 'destroy']);
    Route::resource('expenses', RouhiController::class)
    ->only(['index', 'edit', 'update', 'destroy']);
    Route::resource('bonuses', BonusController::class)
    ->only(['index', 'edit', 'update', 'destroy']);


    

    // 貯金目標のルート
    Route::get('/savegoal', [SavingGoalController::class, 'index'])
    ->name('savegoal.index');
    Route::post('/savegoal', [SavingGoalController::class, 'store'])
    ->name('savegoal.store');





});