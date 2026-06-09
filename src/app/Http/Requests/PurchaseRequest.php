<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'payment_method' => 'required|filled|in:card,konbini', 
            'address' => 'required', 
        ];
    }

    public function messages()
    {
        return [
            'payment_method.required' => '支払い方法を選択してください',
            'payment_method.filled'   => '支払い方法を選択してください',
            'address.required' => '配送先を選択してください',
        ];
    }
    
}
