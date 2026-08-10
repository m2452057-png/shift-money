@isset($shift)
@php
[$startHour, $startMinute] = explode(':', $shift->start_time);
[$endHour, $endMinute] = explode(':', $shift->end_time);

   $breakHour = intdiv((int) $shift->break_duration, 60);
    $breakMinute = (int) $shift->break_duration % 60;
@endphp
@endisset
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>家計簿アプリ</title>

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    @vite(['resources/css/shift-input.css', 
    'resources/js/shift-input.js'])

</head>

<body>

<div class="container">

    <!-- =========================
  シフト・浪費・ボーナスタブ
    ========================== -->
    <div class="card">
    <div class="tabs main-tabs">

        <button
            id="shiftTab"
            class="tab active"
            type="button"
        >
            <i class="bi bi-calendar-check"></i>
            シフト
        </button>

        <button
            id="expenseTab"
            class="tab"
            type="button"
        >
            <i class="bi bi-cart-x"></i>
            浪費
        </button>

        <button
            id="bonusTab"
            class="tab"
            type="button"
        >
            <i class="bi bi-gift"></i>
            ボーナス
        </button>

    </div>


    <!-- =========================
  　　　シフト入力画面
    ========================== -->
    @isset($shift)
    <form method="POST" action="{{ route('shifts.update', ['shift' => $shift->id]) }}">
        @csrf
        @method('PUT')

   

            <h1 class="title">
                <i class="bi bi-calendar-check"></i>
                シフト管理 編集

            </h1>


            <!-- 日付 -->

            <div class="box">
                <label
                    class="label"
                    for="workDate"
                >
                    <i class="bi bi-calendar-event"></i>
                    日付
                </label>

                <input
                    id="work_date"
                    class="input"
                    type="date"
                    value="{{ old('work_date', $shift->work_date) }}"
                >

            </div>


            <!-- 出勤時間・退勤時間 -->

            <div class="shift-times">

                <!-- 出勤時間 -->

                <div class="box">
                    <label
                        class="label"
                        for="startTime"
                    >
                        <i class="bi bi-box-arrow-in-right"></i>

                        出勤時間
                    </label>

                    <div class="time-row">

                        <input
                            id="startTime"
                            class="input"
                            type="number"
                            placeholder="時"
                            min="0"
                            max="23"
                            onwheel="this.blur();"
                            name="start_time"
                            value="{{ old('start_time', $startHour) }}"

                        >

                        <span class="time-colon">：</span>

                        <input
                            id="startminute"
                            class="input"
                            type="number"
                            placeholder="分"
                            min="0"
                            max="59"
                            onwheel="this.blur();"
                            name="start_minute"
                            value="{{ old('start_minute', $startMinute) }}"


                    </div>

                </div>


                <!-- 退勤時間 -->

                <div class="box">

                    <label
                        class="label"
                        for="endTime"
                    >
                        <i class="bi bi-box-arrow-left"></i>

                        退勤時間
                    </label>

                    <div class="time-row">

                        <input
                            id="endTime"
                            class="input"
                            type="number"
                            placeholder="時"
                            min="0"
                            max="23"
                            onwheel="this.blur();"
                            name="end_time"
                            value="{{ old('end_time', $endHour) }}"
                        >

                        <span class="time-colon">：</span>

                        <input
                            id="endminute"
                            class="input"
                            type="number"
                            placeholder="分"
                            min="0"
                            max="59"
                            onwheel="this.blur();"
                            name="end_minute"
                            value="{{ old('end_minute', $endMinute) }}"     

                    </div>

                </div>

            </div>



            <!-- 時給 -->

            <div class="box">

                <label
                    class="label"
                    for="wage"
                >
                    <i class="bi bi-cash-stack"></i>

                    時給
                </label>

                <input
                    id="wage"
                    class="input"
                    type="number"
                    placeholder="例：1160"
                    min="0"
                    onwheel="this.blur();"
                    value="{{ old('wage', $shift->wage) }}"
                >

            </div>


            <!-- 休憩時間 -->

            <div class="box">

                <label
                    class="label"
                    for="breakTime"
                >
                    <i class="bi bi-cup-hot"></i>

                    休憩時間
                </label>

                <div class="time-row">

                    <input
                        id="breakTime"
                        class="input"
                        type="number"
                        placeholder="時間"
                        min="0"
                        onwheel="this.blur();"
                        name="break_time"
                        value="{{ old('break_time', $breakHour) }}"

                    >

                    <span class="time-colon">：</span>

                    <input
                        id="breakminute"
                        class="input"
                        type="number"
                        placeholder="分"
                        min="0"
                        max="59"
                        onwheel="this.blur();"
                        name="break_minute"
                        value="{{ old('break_minute', $breakMinute) }}"

                    >

                </div>

            </div>


            <!-- 保存ボタン -->

            <div class="buttons">

                <button
                    id="saveButton"
                    class="btn save"
                    type="submit"
                >
                    <i class="bi bi-floppy"></i>

                    編集
                </button>

            </div>
            
            <div class="buttons">

                <button
                    id="deleteButton"
                    class="btn delete"
                    type="submit"
                    onclick="return confirm('本当に削除しますか？');"

                >
                    <i class="bi bi-trash"></i>

                    削除
                </button>

            </div>

        </div>
        </form>
    @endisset
       
       

  


    <!-- =========================
    浪費入力画面
    ========================== -->
    @isset($expense)
    <form method="POST" action="{{ route('expenses.update', ['expense' => $expense->id]) }}">
        @csrf
        @method('PUT')

    

       

            <h1 class="title">

                <i class="bi bi-cart-x"></i>

                浪費編集

            </h1>


            <!-- 浪費日 -->

            <div class="box">

                <label
                    class="label"
                    for="expenseDate"
                >
                    <i class="bi bi-calendar-event"></i>

                    日付
                </label>

                <input
                    id="expenseDate"
                    class="input"
                    type="date"
                    value="{{ old('expense_date', $expense->expense_date) }}"       
                >

            </div>


            <!-- 浪費金額 -->

            <div class="box">

                <label
                    class="label"
                    for="expenseTotal"
                >
                    <i class="bi bi-cash"></i>

                    浪費金額
                </label>

                <input
                    id="expenseTotal"
                    class="input"
                    type="number"
                    placeholder="例：3000"
                    name="expense_amount"
                    min="0"
                    onwheel="this.blur();"
                    value="{{ old('expense_amount', $expense->expense_amount) }}"
                >

            </div>


            <!-- 浪費メモ -->

            <div class="box">

                <label
                    class="label"
                    for="expenseMemo"
                >
                    <i class="bi bi-pencil-square"></i>

                    メモ
                </label>

                <textarea
                    id="expenseMemo"
                    class="input textarea"
                    rows="4"
                    placeholder="何に使ったか入力"
                    name="expense_memo"
                    value="{{ old('expense_memo', $expense->expense_memo) }}"
                >
            </textarea>

            </div>


            <!-- 浪費入力ボタン -->

            <div class="buttons">

                <button
                    id="expenseButton"
                    class="btn expense"
                    type="submit"
                >
                    <i class="bi bi-plus-circle"></i>

                    編集
                </button>


            </div>
             <div class="buttons">

                <button
                    id="deleteButton"
                    class="btn delete"
                    type="submit"
                    onclick="return confirm('本当に削除しますか？');"

                >
                    <i class="bi bi-trash"></i>

                    削除
                </button>

            </div>
    </div>
    </form>
    @endisset


    <!-- =========================
         ボーナス入力画面
    ========================== -->
    @isset($bonus)
    <form method="POST" action="{{ route('bonuses.update', ['bonus' => $bonus->id]) }}">
        @csrf
        @method('PUT')
 

    
            <h1 class="title">

                <i class="bi bi-gift"></i>

                ボーナス入力 編集

            </h1>


            <!-- ボーナス日 -->

            <div class="box">

                <label
                    class="label"
                    for="bonusDate"
                >
                    <i class="bi bi-calendar-event"></i>

                    日付
                </label>

                <input
                    id="bonusDate"
                    class="input"
                    type="date"
                    name="bonus_date"
                    value="{{ old('bonus_date', $bonus->bonus_date) }}"
                >

            </div>


            <!-- ボーナス金額 -->

            <div class="box">

                <label
                    class="label"
                    for="bonusTotal"
                >
                    <i class="bi bi-cash-stack"></i>

                    ボーナス金額
                </label>

                <input
                    id="bonusTotal"
                    class="input"
                    type="number"
                    placeholder="例：5000"
                    min="0"
                    onwheel="this.blur();"
                    name="bonus_amount"
                    value="{{ old('bonus_amount', $bonus->bonus_amount) }}"
                >

            </div>


            <!-- ボーナスメモ -->

            <div class="box">

                <label
                    class="label"
                    for="bonusMemo"
                >
                    <i class="bi bi-pencil-square"></i>

                    メモ
                </label>

                <textarea
                    id="bonusMemo"
                    class="input textarea"
                    rows="4"
                    placeholder="ボーナスの内容を入力"
                    name="bonus_memo"
                    value="{{ old('bonus_memo', $bonus->bonus_memo) }}"
                ></textarea>

            </div>


            <!-- ボーナス入力ボタン -->

            <div class="buttons">

                <button
                    id="bonusButton"
                    class="btn bonus"
                    type="submit"
                >
                    <i class="bi bi-plus-circle"></i>

                    編集
                </button>
            </div>
            <button
                    id="deleteButton"
                    class="btn delete"
                    type="submit"
                    onclick="return confirm('本当に削除しますか？');"

                >
                    <i class="bi bi-trash"></i>

                    削除
                </button>
            
            

        </div>

    </div>
   

</div>
 </form>
    @endisset

</body>
</html>