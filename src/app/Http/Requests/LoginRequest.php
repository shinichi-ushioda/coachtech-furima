<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];
    }
    
    public function messages()
    {
        return [
            'email.required' => 'メールアドレスを入力してください', //5月3日　要件追加
            'email.string' => 'メールアドレスは文字列で入力してください',
            'email.email' => 'メールアドレスの形式で入力してください',
            'password.required' => 'パスワードを入力してください', //5月3日　要件追加
            'password.string' => 'パスワードは文字列で入力してください',
            //ログイン情報が登録されていませんのメッセージはLoginControllerのloginメソッドで実装しているため、ここでは定義しない
        ];
    }
}
