<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Shift;
use App\Models\Bonus;
use App\Models\Expense;
use App\Models\MonthlySummary;

class MonthlySummaryController extends Controller
{
    
    public function dashboard(){
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
      // 4. 画面を表示
        return view('login.dashboard', 
        compact(
        'year',
        'month',
        'summary',
        'shiftTotal',
        'expenseTotal',
        'bonusTotal',
        'savingsTotal'
        ));
    }
}