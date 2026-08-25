<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Shift;
use App\Models\Bonus;
use App\Models\Expense;
use App\Models\MonthlySummary;
use App\Models\SavingGoal;

class DashboardController extends Controller
{

public function dashboard(Request $request): View
{  // URLのyearを取得する
    // yearがなければ現在の年を使用する
  $year = $request->input('year', now()->year);
    // URLのmonthを取得する
    // monthがなければ現在の月を使用する
  $month = $request->input('month', now()->month);
    // ログインユーザーのシフトを取得
    $shifts = Shift::where('user_id', Auth::id())
    ->get();
    $expenses = Expense::where('user_id', Auth::id())
    ->get();
    $bonuses = Bonus::where('user_id', Auth::id())
    ->get();

    $events = [];

  foreach ($shifts as $shift) {
    // 「19:00〜0:30」を作る
    $shiftTime = $shift->start_time
        . ':'
        . str_pad($shift->start_minute, 2, '0', STR_PAD_LEFT)
        . '〜'
        . $shift->end_time
        . ':'
        . str_pad($shift->end_minute, 2, '0', STR_PAD_LEFT);

    // 「¥7,043円」を作る
    $shiftSalary = '¥'
        . number_format($shift->salary)
        . '円';

    // 時刻と給与をつなげる
    $title = $shiftTime . "\n" . $shiftSalary;



        // カレンダーへ渡すデータ
    $events[] = [
    'id' => $shift->id,
    'title' => $title,
    'start' => $shift->shift_date,
    'end' => $shift->shift_date,
    'start_time' => $shift->start_time,
    'end_time' => $shift->end_time,


             // 終日予定にしない
    'allDay' => false,
    'backgroundColor' => '#3b82f6',
    'borderColor' => '#3b82f6',
    'url' => route('shifts.edit', ['shift' => $shift->id]),
        ];
        
    }
    foreach($expenses as $expense){
        // 浪費金額から「¥5,000円」を作る
    $expenseAmount = '¥'
    . number_format($expense->expense_amount)
    . '円'
    .($expense->expense_memo ? "\n" . $expense->expense_memo : '');

    // 浪費金額をタイトルにする
    $title = $expenseAmount;

        // カレンダーへ渡すデータ
    $events[] = [
    'id' =>'expense-' .  $expense->id,
    'title' => $title,
    'start' => $expense->expense_date,
    'end' => $expense->expense_date,
    'allDay' => true,
    'backgroundColor' => 'red',
    'borderColor' => 'red',
    'url' => route('expenses.edit', ['expense' => $expense->id]),
        ];
    }
    foreach ($bonuses as $bonus) {
        // ボーナス金額から「¥5,000円」を作る
        $bonusAmount = '¥'
            . number_format($bonus->bonus_amount)
            . '円'
            .($bonus->bonus_memo ? "\n" . $bonus->bonus_memo : '')
            ;

        // ボーナス金額をタイトルにする
        $title = $bonusAmount;

        // カレンダーへ渡すデータ
    $events[] = [
    'id' =>'bonus-' .  $bonus->id,
    'title' => $title,
    'start' => $bonus->bonus_date,
    'end' => $bonus->bonus_date,
    'allDay' => true,
    'backgroundColor' => 'green',
    'borderColor' => 'green',
    'url' => route('bonuses.edit', ['bonus' => $bonus->id]),
        ];
        

    }
    
    $userId = Auth::id();
    $year = now()->year;
    $month = now()->month;
        $shiftTotal = Shift::where('user_id', $userId)
            ->whereYear('shift_date', $year)
            ->whereMonth('shift_date', $month)
            ->sum('salary');
        $bonusTotal = Bonus::where('user_id', $userId)
            ->whereYear('bonus_date', $year)
            ->whereMonth('bonus_date', $month)
            ->sum('bonus_amount');
        $expenseTotal = Expense::where('user_id', $userId)
            ->whereYear('expense_date', $year)
            ->whereMonth('expense_date', $month)
            ->sum('expense_amount');
        $savingsTotal = $shiftTotal + $bonusTotal - $expenseTotal; 
        

        // 今回は合計を計算した後に保存
        $summary = MonthlySummary::updateOrCreate(
        [
            'user_id' => $userId,
            'year' => $year,
            'month' => $month,
        ],
        [
            'shift_total' => $shiftTotal,
            'expense_total' => $expenseTotal,
            'bonus_total' => $bonusTotal,
            'savings_total' => $savingsTotal,
        ]
    );
     



// 貯金金額の１５０万円を取得する(saving_goal->money_savings)
$moneySavings = SavingGoal::where(
  'user_id', 
Auth::id())
->value('money_savings');
// 月額の金額を取得する
$savingTotal = MonthlySummary::where(
  'user_id',Auth::id()
  )->where('year', $year)
  ->where('month','<=',$month)
  ->sum('savings_total');
  // 合計貯金額を計算する
$totalSavings = $moneySavings + $savingTotal;
// 貯金目標の金額を取得する
$moneyGoal = SavingGoal::where(
'user_id', Auth::id())
  ->value('money_goal');
// 残りの貯金目標金額=貯金目標の金額-合計貯金額
$totalgoal = $moneyGoal-$totalSavings;



// Bladeへデータを渡す
    return view('login.dashboard', 
    compact(
    'shiftTotal',
    'bonusTotal',
    'expenseTotal',
    'savingsTotal',
    'bonuses',
    'expenses',
    'events',
    'year',
    'month',
    'summary',
    'totalSavings',
    'totalgoal',
    'moneyGoal'
    ));
}



}
