<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    
    public function rules()
    {
        return [
            // プロフィール画像：jpeg または png のみ
            'image'        => 'nullable|file|mimes:jpeg,png',

            // ユーザー名：必須、20文字以内
            'name'         => 'required|string|max:20',

            // 郵便番号：必須、ハイフンあり8文字（例：123-4567）
            'postal_code'  => 'required|string|size:8|regex:/^\d{3}-\d{4}$/',

            // 住所：必須
            'address'      => 'required',
        ];
    }

    public function messages()
    {
        return [
            'image.mimes'          => 'プロフィール画像はjpegまたはpng形式を選択してください',

            'name.required'        => 'ユーザー名は必須です',
            'name.max'             => 'ユーザー名は20文字以内で入力してください',

            'postal_code.required' => '郵便番号は必須です',
            'postal_code.size'     => '郵便番号はハイフンを含む8文字（例：123-4567）で入力してください',
            'postal_code.regex'    => '郵便番号は「123-4567」の形式で入力してください',

            'address.required'     => '住所は必須です',
        ];
    }
}
