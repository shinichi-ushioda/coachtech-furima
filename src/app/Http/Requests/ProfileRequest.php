<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    
    public function rules(): array
    {
        return [
            // プロフィール画像：jpeg または png のみ
            'img_url'    => 'nullable|file|mimes:jpeg,png',

            // ユーザー名：必須、20文字以内
            'name'         => 'required|max:20',

            // 郵便番号：必須、ハイフンあり8文字（例：123-4567）
            'postal_code'  => 'required|string|size:8|regex:/^\d{3}-\d{4}$/',

            // 住所：必須
            'address'      => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'img_url.mimes'      => 'プロフィール画像はjpegもしくはpng形式を選択してください',

            'name.required'        => 'お名前を入力してください',
            'name.max'             => 'お名前は20文字以内で入力してください',

            'postal_code.required' => '郵便番号は必須です',
            'postal_code.size'     => '郵便番号はハイフンを含む8文字で入力してください',
            'postal_code.regex'    => '郵便番号はハイフンを含む8文字で入力してください',

            'address.required'     => '住所を入力してください',
        ];
    }
}
