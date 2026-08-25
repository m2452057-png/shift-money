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
        <form
            action="{{ route('savegoal.store') }}"
            method="POST"
        >
            @csrf

            <div class="card">
                <h1 class="title">
                    <i class="bi bi-wallet2"></i>
                    貯金目標
                </h1>

  <div class="box">
    <label
    for="money_savings"
    class="label">
    現在の貯金額
    </label>

        <input
          id="money_savings"
          class="input"
          type="number"
          name="money_savings"
          value="{{ old('money_savings',$savingGoals->money_savings ?? '') }}"
          required>
              </div>

    <div class="box">
        <label
        for="money_goal"
        class="label"
        >
        目標金額
        </label>

        <input
          id="money_goal"
          class="input"
          type="number"
          name="money_goal"
          value="{{ old('money_goal',$savingGoals->money_goal ?? '') }}"
          required
          >
    </div>

        <div class="buttons">
          <button class="btn" type="submit">
          保存
          </button>
        </div>
    </div>
  </form>
  </div>
</body>

</html>