<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlySummary extends Model
{
    protected $table = 'monthly_summaries'; // テーブル名を指定
    protected $fillable = [
        'user_id',
        'year',
        'month',
        'shift_total',
        'expense_total',
        'bonus_total',
        'savings_total',
    ];
    // 指定したユーザー・年月の集計を作るためメソッド
    public static function calculate(
        int $userId, 
        int $year, 
        int $month)
    {// 指定したユーザー・年・月の給料を合計する
     // 指定月の給料を合計
        $shiftTotal = Shift::where('user_id', $userId)
            ->whereYear('shift_date', $year)
            ->whereMonth('shift_date', $month)
            ->sum('salary');
 // 指定月の支出を合計
        $expenseTotal = Expense::where('user_id', $userId)
            ->whereYear('expense_date', $year)
            ->whereMonth('expense_date', $month)
            ->sum('expense_amount');
 // 指定月のボーナスを合計
        $bonusTotal = Bonus::where('user_id', $userId)
            ->whereYear('bonus_date', $year)
            ->whereMonth('bonus_date', $month)
            ->sum('bonus_amount');
            // 月の貯金額を計算
        $savingsTotal = $shiftTotal + $bonusTotal - $expenseTotal;


// monthly_summariesへ保存する
        return self ::updateOrCreate(
            [
                'user_id' => $userId,
                'year' => $year,
                'month' => $month,
            ],
            [
                'shift_total' => $shiftTotal,
                'expense_total' => $expenseTotal,
                'bonus_total' => $bonusTotal,
                'savings_total' =>$shiftTotal + $bonusTotal - $expenseTotal,
            ]
        );
    }
}
