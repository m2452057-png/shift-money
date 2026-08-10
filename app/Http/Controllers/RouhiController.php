<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Expense;


class RouhiController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $expenses = Expense::where('user_id', $user->id)->get();
        return view('login.rouhi-input', compact('expenses'));
    }

    public function store(Request $request)
    {
        // フォームの入力内容をバリデーションする
        $validated = $request->validate([
            'expense-date' => 'required|date',
            'expense-amount' => 'required|integer|min:0',
            'expense-memo' => 'nullable|string|max:255',
        ]);

        // DB保存
        Expense::create([
            'user_id' => Auth::id(),
            'expense_date' => $validated['expense-date'],
            'expense_amount' => $validated['expense-amount'],
            'expense_memo' => $validated['expense-memo'] ?? null,
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', '経費情報が保存されました。');
    }
    public function edit(Expense $expense)
    {
        abort_unless($expense->user_id === Auth::id(), 403);
        return view('login.shift-edit', [
            'expense' => $expense,
            'activeTab' => 'expense',
        ]);
    }
}
