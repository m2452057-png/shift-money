<!DOCTYPE html>
<!-- ログイン画面 -->
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ログイン</title>

 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@vite([
    'resources/css/login.css',
    'resources/js/app.js'
])
</head>
<body>

<div class="login-container">
    <h1 class="app-title">
<i class="bi bi-wallet2"></i>
Shift Money
</h1>



<p class="app-subtitle">
シフト・家計簿管理アプリ
</p>
    <div class="login-card">
<form method="POST" action="{{ route('login.login') }}">
@csrf
@error('login')

    <div class="text-danger">
        {{ $message }}
    </div>

@enderror

<label for="email" class="form-label">ユーザーID（メールアドレス）</label>

<input 
    type="text" 
    class="login-input" 
    id="email" 
    name="email"
    value="{{ old('email') }}"
    placeholder="ユーザーID（メールアドレス）"
    required
    autofocus
    >
    @error('email')
        <div class="text-danger">
            {{ $message }}
        </div>
    @enderror

<label for="password" class="sr-only">パスワード</label>
<input 
    class="login-input" 
    id="password" 
    type="password"  
    name="password" 
    placeholder="パスワード"
    autocomplete="current-password"
    required
    >
    {{-- パスワードのエラー --}}
    @error('password')
        <div class="text-danger">
            {{ $message }}
        </div>
    @enderror
    </label>
     <a href="{{ route('newlogin') }}">新規登録</a>
<button type="submit" class="login-button">
    <i class="bi bi-box-arrow-in-right"></i>
    ログイン
</button>
</form>

    </div>
    </div>
</body>
</html>
