<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdressRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'postal_code' => 'required|string|size:8|regex:/^\d{3}-\d{4}$/', //郵便番号入力必須、ハイフンありの形式
            'address' => 'required', //配送先入力必須
            
        ];
    }

    public function messages()
    {
        return [
            'postal_code.required' => '郵便番号を入力してください',
            'postal_code.size'     => '郵便番号はハイフンを含む8文字（例：123-4567）で入力してください',
            'postal_code.regex'    => '郵便番号は「123-4567」の形式で入力してください',
            'address.required' => '配送先を入力してください',
        ];
    }
}
