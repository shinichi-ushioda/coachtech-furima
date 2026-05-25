<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
       public function authorize(): bool
    {
        return true;
    }

       public function rules(): array
    {
        return [
            'name' => 'required|max:20',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'お名前を入力してください', //5月3日　要件追加
            'name.max' => 'ユーザー名は20文字以内で入力してください',
            'email.required' => 'メールアドレスを入力してください', //5月3日　要件追加
            'email.unique' => 'このメールアドレスは既に使用されています',
            'email.email' => 'メールアドレスはメール形式で入力してください',
            'password.required' => 'パスワードを入力してください', //5月3日　要件追加
            'password.min' => 'パスワードは8文字以上で入力してください', //5月3日　要件追加
            'password.confirmed' => 'パスワードと一致しません',
        ];
    }
}
