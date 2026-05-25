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
            $request->validated();

            // 認証処理
            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }

            // 認証失敗時の日本語エラー
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => ['ログイン情報が登録されていません'],
            ]);
        });

        // -----------------------------
        // ③ ★ Fortify の内部バリデーションを完全停止
        // -----------------------------
        $this->app->singleton(LoginResponse::class, function () {
            return new class implements LoginResponse {
                public function toResponse($request)
                {
                    return redirect()->intended('/');
                }
            };
        });
    }
}
