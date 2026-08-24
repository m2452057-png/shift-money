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
            'money_savings' => 'nullable|integer|min:0',
            'money_goal' => 'nullable|integer|min:0',
        ]);

        // DB保存
        $updated=[];
        if ($request->filled('money_savings')) {
            $updated['money_savings'] = $validated['money_savings'];
        }
        if ($request->filled('money_goal')) {
            $updated['money_goal'] = $validated['money_goal'];
        }
        if($updated!==[]){
            SavingGoal::updateOrCreate(
                ['user_id' => Auth::id()],
                $updated
            );
        }


        return redirect()
            ->route('dashboard')
            ->with('success', '貯金目標が保存されました。');
    }

}