<?php

namespace App\Http\Controllers;

   use App\Models\User;
   use Illuminate\Http\Request;
   use Illuminate\Support\Facades\Auth;
   use Illuminate\Support\Facades\Hash;
   use Illuminate\Foundation\Auth\EmailVerificationRequest;
   use App\Http\Requests\RegisterRequest;

   class AuthController extends Controller
{
    //register表示
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // ユーザー登録処理
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'user_name' => $request->user_name,  
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 3,
        ]);

        event(new \Illuminate\Auth\Events\Registered($user));

        Auth::login($user);

       return redirect()->route('verification.notice');
    }

    // Thanksページを表示
    public function showThanksPage()
    {
        return view('auth.thanks');
    }

    // メール認証処理
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        // メール認証後に thanks ページへリダイレクト
        return redirect()->route('thanks');
    }


    //login表示
    public function showLoginForm()
    {
        return view('auth.login');
    }


    // ログイン処理
    public function login(Request $request)
    {
        $messages = [
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => '有効なメールアドレスを入力してください。',
            'password.required' => 'パスワードは必須です。',
        ];

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ],$messages);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (!Auth::user()->hasVerifiedEmail()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'メールアドレスが認証されていません。認証メールを確認してください。',
                ]);
            }

            // ユーザーのロールに基づいてリダイレクト先を決定
            switch (Auth::user()->role) {
                case 1: // admin
                    return redirect()->route('admin.dashboard');
                case 2: // shop_manager
                    return redirect()->route('shop_manager.dashboard');
                default:
                    return redirect()->route('shops.index'); // 一般ユーザーは店舗一覧ページへ
            }
        }

        return back()->withErrors([
            'email' => '指定された認証情報が登録情報と一致しません。',
        ]);
    }

    // ログアウト処理
    public function logout(Request $request)
    {
        $request->session()->forget('reservation_details');

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    //ユーザーのロールに基づいて適切なダッシュボードにリダイレクト
    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 1) {
            return redirect()->route('admin.dashboard'); // 管理者ダッシュボードへリダイレクト
        } elseif ($user->role === 2) {
            return redirect()->route('shop_manager.dashboard'); // ショップマネージャーダッシュボードへリダイレクト
        } else {
            return redirect()->route('index'); // 一般ユーザーはホームページへリダイレクト
        }
    }
}