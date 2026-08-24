<?php
namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;





// PHPの処理


class ShiftController extends Controller{
  public function index():View
  {
      $user = Auth::user();
      $shifts = Shift::where('user_id', $user->id)->get();
      return view('login.shift-input', compact('shifts'));
  }

    // シフト登録画面
    public function create(): View
    {
        return view('login.shift-input');
    }

public function store(Request $request)
{  
  
// フォームの入力内容をバリデーションする
    $validated = $request->validate([
        'work_date' => 'required|date',
        'start_time' => 'required|integer|min:0|max:23',
        'start_minute' => 'required|numeric|min:0|max:59',
        'end_time' => 'required|integer|min:0|max:23',
        'end_minute' => 'required|numeric|min:0|max:59',
        'wage' => 'required|integer|min:0',
        'break_time' => 'nullable|integer|min:0',
        'break_minute' => 'nullable|numeric|min:0|max:59',
        ]);
    // 入力されていない休憩時間は0にする
    $breakTime = (int)($validated['break_time'] ?? 0);
    $breakMinute = (int)($validated['break_minute'] ?? 0);


    // 入力してチェック通った数字 1日分の給与を計算する
    // その後 public function shiftDailyに受け渡す
      $salary = $this->shiftDaily(
      (int)$validated['wage'],
      (int)$validated['start_time'],
      (int)$validated['start_minute'],
      (int)$validated['end_time'],
      (int)$validated['end_minute'],
      $breakTime,
      $breakMinute

      );
     //  DB保存

    // シフトの保存DBに保存するための処理
    Shift::create([
        'user_id' => Auth::id(),

        'shift_date' => $validated['work_date'],
        'start_time' => $validated['start_time'],
        'start_minute' => $validated['start_minute'],
        'end_time' => $validated['end_time'],
        'end_minute' => $validated['end_minute'],
        'wage' => $validated['wage'],
        'break_time' => $breakTime,
        'break_minute' => $breakMinute,
        'salary' => $salary,
    ]);

    return redirect()->route('dashboard')->with('success', 'シフトが保存されました。');
  }





// シフトの勤務時間から1日分の給与を計算するメゾット

public function shiftDaily(
int $wage,
int $startTime,
int $startminute, 
int  $endTime,
int $endminute,
int $breakTime,
int $breakminute 

):int{
  // 分を時間へ変換
  // 17.5時 =17時+ （30分/60分）時
    $startHours = $startTime+$startminute /60;
    // 　　　　11時　＝11時＋（0/60）
    $endHours = $endTime +$endminute / 60;
    $breakHours=($breakTime+$breakminute/60);

     // 合計金額
        $total = 0;
if($endHours < $startHours){
   // 例　開始:17.30 退勤：11　３５=２４＋１１.5
$endHours = $endHours + 24;
}


if($startHours>=22 &&$endHours<=29){
  $total=$wage*($endHours-$startHours-$breakHours)*1.25;
}else if($startHours>=22){
  $normalWage=$wage*(29-$startHours)*1.25;
  $morningWage=$wage*($endHours-29);
  $breakWage=$breakHours*1.25*$wage;
  $total=$normalWage+$morningWage-$breakWage;

}else if($endHours>=22){
  if($endHours>=29){
     // ＊＊通常料金①＊＊
    // 5400 ＝（２２−１７.5）*１２００
  $normalWage=(22-$startHours)*$wage;

// ＊＊＊深夜料金(22～5)＊＊＊
// 10500＝１２００*1.25（29-22)
  $midhours=29-22;
  $midwages=$wage*1.25*$midhours;
  
  // 通常料金②(5時以降)
  //   7200=(0.5＋35-29)*1200
  $morningWage=(($endHours)-29)*$wage;
  
  $breakWage = $breakHours * 1.25 * $wage;
  $total = $normalWage + $midwages + $morningWage - $breakWage;

}else{ // 1 = 23 - 22
    $lasthours = $endHours - 22;

    // 1500 = 1200 * 1.25 * 1
    $midwages = $wage * 1.25 * $lasthours;
    // ⬆️ 深夜料金はここまで

    // 4800 = 4 * 1200
    $normalWage = (22 - $startHours) * $wage;

    // ここまでが通常料金
    $breakWage = $breakHours * 1.25 * $wage;
    
    // 6300 = 1500 + 4800
    $total = $normalWage + $midwages - $breakWage;

}

}else{

    $total = $wage * ($endHours - $startHours - $breakHours);

}
return (int) floor($total);

}

public function edit(Shift $shift): View
{
  abort_unless($shift->user_id === Auth::id(), 403);
  return view('login.shift-edit', [
    
        'shift' => $shift,
        'activeTab' => 'shift',
  ]);
        
    }

  
  
    public function update(Request $request, Shift $shift)
  {
    abort_unless($shift->user_id === Auth::id(), 403);

    // フォームの入力内容をバリデーションする
    $validated = $request->validate([
        'work_date' => 'required|date',
        'start_time' => 'required|integer|min:0|max:23',
        'start_minute' => 'required|numeric|min:0|max:59',
        'end_time' => 'required|integer|min:0|max:23',
        'end_minute' => 'required|numeric|min:0|max:59',
        'wage' => 'required|integer|min:0',
        'break_time' => 'nullable|integer|min:0',
        'break_minute' => 'nullable|numeric|min:0|max:59',
    ]);
    $breakTime = (int)($validated['break_time'] ?? 0);
    $breakMinute = (int)($validated['break_minute'] ?? 0);

    $salary = $this->shiftDaily(
      (int)$validated['wage'],
      (int)$validated['start_time'],
      (int)$validated['start_minute'],
      (int)$validated['end_time'],
      (int)$validated['end_minute'],
      $breakTime,
      $breakMinute

      );
      $shift->update([
        'shift_date' => $validated['work_date'],
        'start_time' => $validated['start_time'],
        'start_minute' => $validated['start_minute'],
        'end_time' => $validated['end_time'],
        'end_minute' => $validated['end_minute'],
        'wage' => $validated['wage'],
        'break_time' => $breakTime,
        'break_minute' => $breakMinute,
        'salary' => $salary,
    ]);

    return redirect()
        ->route('dashboard')
        ->with('success', 'シフト情報が更新されました。');
  }
  public function destroy(Shift $shift)
  {
      abort_unless($shift->user_id === Auth::id(), 403);
      $shift->delete();
      return redirect()
          ->route('dashboard')
          ->with('success', 'シフト情報が削除されました。');
    }



}

