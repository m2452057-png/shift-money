<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function index()
    { // ログイン画面
        return view('login.index');
    }
    public function newlogin()
    { // 新規登録画面
        return view('login.newlogin');
    }
    public function create(Request $request)
    {
        // 新規登録処理
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
        ], ['name.required' => '名前は必須です。',
            'name.string' => '名前は文字列で入力してください。',
            'name.max' => '名前は255文字以内で入力してください。',
            'email.required' => 'メールアドレスは必須です。',
            'email.email' => '有効なメールアドレスを入力してください。',
            'email.unique' => 'このメールアドレスは既に登録されています。',
            'password.required' => 'パスワードは必須です。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
        ]);
        user::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);
        return redirect()
            ->route('login.index')
            ->with('success', '新規登録が完了しました。ログインしてください。');
    }

      // ログイン処理
    public function login(Request $request)
    {
        $validated = $request->validate(
            [
                'email' => ['required', 'email'],
                'password' => ['required'],
            ],
            [
                'email.required' => 'ユーザーIDは必須です。',
                'email.email' => '有効なメールアドレスを入力してください。',
                'password.required' => 'パスワードは必須です。',
            ]
        );

        try {
            $authenticated = Auth::attempt($validated, $request->boolean('remember'));
        } catch (\Throwable $exception) {
            $summaries = [];
            $current = $exception;

            for ($depth = 0; $current !== null && $depth < 5; $depth++) {
                $summaries[] = sprintf(
                    '%s(code=%s) at %s:%d',
                    $current::class,
                    (string) $current->getCode(),
                    basename($current->getFile()),
                    $current->getLine(),
                );
                $current = $current->getPrevious();
            }

            error_log('LOGIN_ERROR '.implode(' <- ', $summaries));

            throw $exception;
        }

        if ($authenticated) {
            $request->session()->regenerate();

            return redirect('/dashboard');
        }

        return back()
            ->withErrors([
                'login' => 'メールアドレスまたはパスワードが違います。',
            ])
            ->onlyInput('email'); 
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('login');
    }
}
