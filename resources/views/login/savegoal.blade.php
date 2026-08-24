<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
  

    <title>貯金</title>

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
       
    >
    <link
        rel="stylesheet"
        href="{{ asset('css/savegoal.css') }}?v=2"
    >
    @vite(['resources/js/app.js'])
</head>
<body> 
    <div class="container">
        <form method="POST" action="{{ route('savegoal.store') }}">

        @csrf
            {{-- 入力エラーがある場合にメッセージを表示する --}}
    @if ($errors->any())
        <div class="error-messages">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
      <div class="card">
        <h1 class="title common-title">
        <i class="bi bi-bar-chart-line-fill text-primary"></i>
      貯金額
        </h1>
    <div class="box">
      <label for="money_savings" class="label">現在貯金額</label>
      <input 
      type="number" 
      id="money_savings" 
      name="money_savings"
      class="input" 
      min="0"
      value="{{ old('money_savings',
      $savingGoals?->money_savings ?? '') }}"
      placeholder="例: 100000">
    </div>
    <div class="buttons">
      <button 
      class="btn save-btn"
      type="submit">保存</button>
    </div>
  </div>
</form>
  
  <form method="POST" action="{{ route('savegoal.store') }}">
        @csrf
        
            {{-- 入力エラーがある場合にメッセージを表示する --}}
    @if ($errors->any())
        <div class="error-messages">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
      <div class="card">
      <h1 class="title common-title">
        <i class="bi bi-bar-chart-line-fill text-primary"></i>
      目標貯金額
      </h1>
    <div class="box">
        <label 
        for="money_goal" 
        class="label">目標貯金額</label>
        <input 
        type="number" 
        id="money_goal" 
        name="money_goal"
        value="{{ old('money_goal',
        $savingGoals?->money_goal ?? '') }}"
        class="input"
      placeholder="例: 150000"
      >
    </div>
      <div class="buttons">
      <button type="submit" class="btn save-btn">保存</button>
    </div>
  </div>
  </form>


</div>



  
</body>

</html>