<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // ログイン処理の実装 5月3日
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if(!auth()->attempt($credentials)){
            return back()->withErrors([
                'email' => 'ログイン情報が登録されていません',
            ])->withInput();
        }
        return redirect()->to('/'); // ログイン成功後のリダイレクト先
    }
}
