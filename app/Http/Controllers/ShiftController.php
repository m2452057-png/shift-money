<?php
namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;
use App\Models\Bonus;
use App\Models\Expense;
use App\Models\MonthlySummary;



// PHPの処理


class ShiftController extends Controller{
  public function index():View
  {
      $user = Auth::user();
      $shifts = Shift::where('user_id', $user->id)->get();
      return view('login.shift-input', compact('shifts'));
  }
public function store(Request $request)
{ 
  
// フォームの入力内容をバリデーションする
    $validated = $request->validate([
        'work-date' => 'required|date',
        'start-time' => 'required|integer|min:0|max:23',
        'start-minute' => 'required|numeric|min:0|max:59',
        'end-time' => 'required|integer|min:0|max:23',
        'end-minute' => 'required|numeric|min:0|max:59',
        'wage' => 'required|integer|min:0',
        'break-time' => 'nullable|integer|min:0',
        'break-minute' => 'nullable|numeric|min:0|max:59',
        ]);
    // 入力されていない休憩時間は0にする
    $breakTime = (int)($validated['break-time'] ?? 0);
    $breakMinute = (int)($validated['break-minute'] ?? 0);


    // 入力してチェック通った数字 1日分の給与を計算する
    // その後 public function shiftDailyに受け渡す
      $salary = $this->shiftDaily(
      (int)$validated['wage'],
      (int)$validated['start-time'],
      (int)$validated['start-minute'],
      (int)$validated['end-time'],
      (int)$validated['end-minute'],
      $breakTime,
      $breakMinute

      );
     //  DB保存
   // 休憩時間を分単位に変換 DBに保存するため DBはunsignedInteger(整数)だから
     $breakDuration = $breakTime * 60 + $breakMinute;
    // シフトの保存DBに保存するための処理
    Shift::create([
        'user_id' => Auth::id(),
        // 出勤時間を『時』:『分』の形式に変換して保存する
        // sprintfは、複数の値を文字列にまとめるPHPの関数
        // %02dは、数字を2桁で表示し、1桁の場合は先頭に0を付ける
        'shift_date' => $validated['work-date'],
        'start_time' => sprintf(
          '%02d:%02d', 
            $validated['start-time'],
            $validated['start-minute']
          ),
          // 退勤時間を「時:分」の形式にする
        'end_time' => sprintf(
          '%02d:%02d',
            $validated['end-time'],
            $validated['end-minute']
          ),
        'wage' => (int)$validated['wage'],
        'break_duration' => $breakDuration,
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
// =================================
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
        // 開始時刻と終了時刻から「19:00〜00:50」を作る
        $shiftTime = substr($shift->start_time, 0, 5)
            . '〜'
            . substr($shift->end_time, 0, 5);

        // 給与から「¥7,522円」を作る
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
            .($expense->expense_memo ? "\n" . $expense->expense_memo : '')
            ;

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

    // 選択された年月の合計を計算・保存する
    $summary = MonthlySummary::calculate
    (Auth::id(), $year, $month);

// Bladeへデータを渡す
    return view('login.dashboard', 
    compact(
    'events',
    'year',
    'month',
    'summary',
    ));
}




// ==================================
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
        'break_duration' => 'nullable|integer|min:0',
    ]);
    $breakTime = (int)($validated['break_duration'] ?? 0);
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
        'start_time' => sprintf(
            '%02d:%02d',
            $validated['start_time'],
            $validated['start_minute']
        ),
        'end_time' => sprintf(
            '%02d:%02d',
            $validated['end_time'],
            $validated['end_minute']
        ),
        'wage' => (int)$validated['wage'],
        'break_duration' => $breakTime * 60 + $breakMinute,
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

