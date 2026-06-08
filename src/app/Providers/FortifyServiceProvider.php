<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse; // 新規登録後も制御したい場合
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // -----------------------------
        // ① Fortify の標準設定
        // -----------------------------
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // ログイン画面
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // 新規登録画面
        Fortify::registerView(function () {
            return view('auth.register');
        });
        
        //メール認証待ち画面（見本と同じ画面）を指定する
        Fortify::verifyEmailView(function () {
        return view('auth.verify_email');
        });

        // ログイン試行回数制限
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(
                Str::lower($request->input(Fortify::username())) . '|' . $request->ip()
            );
            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // -----------------------------
        // ② ★ ログイン処理を完全上書き（日本語バリデーション）
        // -----------------------------
        Fortify::authenticateUsing(function ($request) {

            // LoginRequest の日本語バリデーションを実行
             $request->validate((new \App\Http\Requests\LoginRequest)->rules(),
                       (new \App\Http\Requests\LoginRequest)->messages());

            // 認証処理
            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }

            // ログイン認証失敗時の日本語エラー
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => ['ログイン情報が登録されていません'],
            ]);
        });

        // -----------------------------
        // ③ ★ ログイン・新規登録後のリダイレクト先を設定
        // -----------------------------
        
        // 🔓 ログイン画面からログインした場合は、通常のトップページへ
        $this->app->singleton(LoginResponse::class, function () {
            return new class implements LoginResponse {
                public function toResponse($request)
                {
                     // ログインしたユーザーがまだメール認証を完了していない場合
                    if (!auth()->user()->hasVerifiedEmail()) {
                        return redirect()->route('verification.notice');
                    }
                    // もともと設定されていた通常の動き（トップページへ）
                    return redirect()->intended('/');
                }
            };
        });
 
        // 📝 会員登録ボタンから新規登録した場合は、プロフィール設定画面へ
        $this->app->singleton(RegisterResponse::class, function () {
            return new class implements RegisterResponse {
                public function toResponse($request)
                {
                    // 新規登録の直後だけプロフィール編集画面へ遷移させる
                    return redirect()->route('verification.notice');
                }
            };
        });


        
    }
}
