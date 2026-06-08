<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
            // 6月4日追記：ログインしているが、メール認証がまだの場合は認証画面へリダイレクト
            if (!Auth::guard($guard)->user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            // メール認証済みの場合は、設定されたHOME（プロフィール等）へ
            return redirect(config('fortify.home', '/'));
        }

        return $next($request);
    }
    }
}