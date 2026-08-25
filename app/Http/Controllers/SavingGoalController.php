<?php

namespace App\Http\Controllers;
use App\Models\SavingGoal;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

use Illuminate\Http\Request;

class SavingGoalController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $savingGoals = SavingGoal::
        where('user_id', $user->id)->first();
        return view('login.savegoal', compact(
            'savingGoals',
        ));
    }
    public function store(Request $request)
    {
        // フォームの入力内容をバリデーションする
        $validated = $request->validate([
            'money_savings' => 'required|integer|min:0',
            'money_goal' => 'required|integer|min:0',
        ]);

        // DB保存
        
        SavingGoal::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'money_savings' => $validated['money_savings'],
                'money_goal' => $validated['money_goal'],
            ]
        );



        return redirect()
            ->route('dashboard')
            ->with('success', '貯金目標が保存されました。');
    }

}