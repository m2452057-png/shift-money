<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >


    <title>ダッシュボード</title>

    {{-- アイコンを使うための読み込み --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    {{-- dashboard.cssを読み込む --}}
    @vite('resources/css/dashboard.css')
    {{-- app.jsを読み込む --}}
    @vite( 
    'resources/js/app.js')

    {{-- FullCalendarのCSSを読み込む --}}
    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.css"
>
</head>

<body>

    {{-- 画面全体 --}}
    <div class="layout">

    {{-- 左側のメニュー --}}
    <aside class="sidebar" id="sidebar">

    <div>

    {{-- アプリ名 --}}
    <h2>
    <i class="bi bi-wallet2"></i>
    Shift Money
  
    </h2>

    {{-- メニュー --}}
        <nav id="sidebar-menu">

        <a href="{{ route('dashboard') }}">
        <i class="bi bi-house"></i>
        ホーム
        </a>

        <a href="#">
        <i class="bi bi-bar-chart"></i>
        月別収支
        </a>

        <a href="{{ route('savegoal.index') }}">
        <i class="bi bi-piggy-bank"></i>
        貯金目標
        </a>
        </nav>
            </div>


            {{-- サイドバーの一番下 --}}
        <div class="sidebar-bottom">

            {{-- ログインしている人の名前 --}}
            <p>
            {{ Auth::user()->name }}
            </p>

            {{-- ログアウトボタン --}}
            <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit">
            <i class="bi bi-box-arrow-left"></i>
            ログアウト
            </button>
            </form>
            </div>
    </aside>
        <div class="top-space">
            <button id="menu-button" type="button">☰</button>
        </div>

                {{-- 右側の画面 --}}
                <main class="main">
                <h1>ダッシュボード</h1>

        {{-- 4つの金額カード --}}
            <div class="cards"
            style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; width: 100%;">

                <div class="card">
                <p>{{ $year }}年{{ $month }}月のシフト収入</p>
                @if ($shiftTotal== 0)
                <h2>未登録</h2>
                @else
                <h2>+{{ number_format($shiftTotal) }}円</h2>
                @endif
                </div>
            

                <div class="card"style="color: #25eb8b;}">
                <p>{{ $year }}年{{ $month }}月のボーナス</p>
                @if ($bonusTotal == 0)
                <h2 style="color: #25eb8b;">未登録</h2>
                @else
                <h2 style="color: #25eb8b;">+{{ number_format($bonusTotal) }}円</h2>
                @endif
                </div>

                <div class="card"style="color: #ff0000;}">
                <p>{{ $year }}年{{ $month }}月の浪費</p>
                @if (($summary->expense_total??0) == 0)
                <h2 style="color: #ff0000;">未登録</h2>
                @else
                <h2 style="color: #ff0000;">-{{ number_format($expenseTotal) }}円</h2>
                @endif
                </div>

                <div class="card">
                <p>{{ $year }}年{{ $month }}月額</p>
                @if ($savingsTotal == 0)
                <h2 style="color: #7C3AED;">未登録</h2>
                @else
                <h2 style="color: #7C3AED;">{{ number_format($savingsTotal) }}円</h2>
                @endif
                </div>
                <div class="card">
                <p>{{ $year }}年{{ $month }}月の貯金額</p>
                @if ($totalSavings == 0)
                <h2 style="color:#059669;">未登録</h2>
                @else
                <h2 style="color:#059669;">{{ number_format($totalSavings) }}円</h2>
                @endif
                </div>

                <div class="card">
                    <p>{{ number_format($moneyGoal) }}目標まで残り</p>
                    <h2 style="color:#EA580C;">{{ number_format($totalgoal) }}円</h2>
                </div>


            <div class="card" style="color:#000000;">
                <p>{{ $year }}年平均月収{{ number_format($saveAverage) }}円</p>
                @if ($averageTotal == 0)
                <h2>未登録</h2>
                @else
                <h2>{{ number_format($averageTotal, 2) }}ヶ月</h2>
                @endif
            </div>
            <div class="card" style="color:#000000;">
                <p>{{ $year }}年最大月収{{ number_format($max) }}円</p>
                @if ($maxTotal == 0)
                <h2>未登録</h2>
                @else
                <h2>{{ number_format($maxTotal, 2) }}ヶ月</h2>
                @endif
            </div>
             <div class="card" style="color:#000000;">
                <p>{{ $year }}年最小月収{{ number_format($min) }}円</p>
                @if ($min == 0)
                <h2>未登録</h2>
                @else
                <h2>{{ number_format($minTotal, 2) }}ヶ月</h2>
                @endif
            </div>

            </div>

                    {{-- 下の大きな場所 --}}
                    <div class="content">
                    <h2>スケジュール</h2>

                    <div id="calendar">

                    </div>
                        </div>

        </main>

    </div>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.9.0/locales/ja.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarElement = document.getElementById('calendar');

        const calendar = new FullCalendar.Calendar(calendarElement, {
            initialView: 'dayGridMonth',
            // ここから下はネットに頼ってしまった
            initialDate: "{{sprintf('%04d-%02d-01', $year, $month)}}",
            datesSet: function (info) {
    const date = info.view.calendar.getDate();
    const displayedYear = date.getFullYear();
    const displayedMonth = date.getMonth() + 1;

    const currentYear = {{ $year }};
    const currentMonth = {{ $month }};

    if (
        displayedYear !== currentYear ||
        displayedMonth !== currentMonth
    ) {
        window.location.href =
            "{{ route('dashboard') }}"
            + "?year=" + displayedYear
            + "&month=" + displayedMonth;
    }
    //ここはネットに頼ってしまった ここで、表示されている月が変わったときにページをリロードして、正しい月のデータを取得する
},
            locale: 'ja',
            firstDay: 1,
            height: 'auto',
            fixedWeekCount: false,
            showNonCurrentDates: false,
            eventDisplay: 'block',
            displayEventEnd: true,
            

            // Controllerから渡された予定
            events: @json($events ?? []),
            // イベントタイトル内の<br>をHTMLの改行として表示する
            eventContent: function (info) {
                return {html: info.event.title.replace(/\n/g, '<br>')};},

            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },

            buttonText: {
                today: '今日',
                month: '月',
                week: '週',
                day: '日',
                list: 'リスト'
            },

            noEventsContent: '予定はありません',

            dateClick: function (info) {
                        // 日付をクリックしたときの処理
                        const date = info.dateStr;
                        const url = "{{ route('shift-input') }}?date=" + date;
                        window.location.href = url;
                    },

            eventClick: function (info) {
                // イベント編集画面がある場合に利用できます
                if (info.event.url) {
                    info.jsEvent.preventDefault();
                    window.location.href = info.event.url;
                }
            }
        });

        calendar.render();
    });
</script>
</body>

</html>
