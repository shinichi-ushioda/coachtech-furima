<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
       public function authorize()
    {
        return true;
    }

       public function rules()
    {
        return [
            'name' => 'required|string|max:20',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8|same:password',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'お名前を入力して下さい', //5月3日　要件追加
            'name.string' => 'ユーザー名は文字列で入力してください',
            'name.max' => 'ユーザー名は20文字以内で入力してください',
            'email.required' => 'メールアドレスを入力してください', //5月3日　要件追加
            'email.unique' => 'このメールアドレスは既に使用されています',
            'email.email' => 'メールアドレスの形式で入力してください。',
            
            'password.required' => 'パスワードを入力してください', //5月3日　要件追加
            'password.string' => 'パスワードは文字列で入力してください',
            'password.min' => 'パスワードは8文字以上で入力してください', //5月3日　要件追加
            'password.confirmed' => 'パスワード確認と一致しません',
            'password_confirmation.required' => 'パスワード確認は必須です',
            'password_confirmation.string' => 'パスワード確認は文字列で入力してください',
            'password_confirmation.min' => 'パスワード確認は8文字以上で入力してください',
            'password_confirmation.same' => 'パスワードと一致しません', //5月3日　要件追加
        ];
    }
}
