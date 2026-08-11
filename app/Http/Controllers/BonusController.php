<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Bonus;

class BonusController extends Controller{
  public function index():View
  {
    $user = Auth::user();
    $bonuses = Bonus::where('user_id', $user->id)->get();
    return view('login.bonus-input', compact('bonuses'));
  }

public function store(Request $request)
{ 
  // フォームの入力内容をバリデーションする
    $validated = $request->validate([
        'bonus-date' => 'required|date',
        'bonus-amount' => 'required|integer|min:0',
        'bonus-memo' => 'nullable|string|max:255',
        ]);

    // DB保存
    Bonus::create([
        'user_id' => Auth::id(),
        'bonus_date' => $validated['bonus-date'],
        'bonus_amount' => $validated['bonus-amount'],
        'bonus_memo' => $validated['bonus-memo'] ?? null,
    ]);
    return redirect()
    ->route('dashboard')
    ->with('success', 'ボーナス情報が保存されました。');
}
public function edit(Bonus $bonus)
{abort_unless($bonus->user_id === Auth::id(), 403);
    return view('login.shift-edit', [
        'bonus' => $bonus,
        'activeTab' => 'bonus',
    ]);

}
public function update(Request $request, Bonus $bonus)
{
    abort_unless($bonus->user_id === Auth::id(), 403);

    // フォームの入力内容をバリデーションする
    $validated = $request->validate([
        'bonus_date' => 'required|date',
        'bonus_amount' => 'required|integer|min:0',
        'bonus_memo' => 'nullable|string|max:255',
    ]);

    // DB更新
    $bonus->update([
        'bonus_date' => $validated['bonus_date'],
        'bonus_amount' => $validated['bonus_amount'],
        'bonus_memo' => $validated['bonus_memo'] ?? null,
    ]);

    return redirect()
        ->route('dashboard')
        ->with('success', 'ボーナス情報が更新されました。');

}
public function destroy(Bonus $bonus)
{
    abort_unless($bonus->user_id === Auth::id(), 403);

    // DB削除
    $bonus->delete();

    return redirect()
        ->route('dashboard')
        ->with('success', 'ボーナス情報が削除されました。');
}
}