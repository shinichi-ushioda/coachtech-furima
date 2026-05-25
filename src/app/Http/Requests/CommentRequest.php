<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
     * 5月16日追加！！
     */
class CommentRequest extends FormRequest
{
    /**
     * ユーザーがこのリクエストを実行する権限があるか（FN020: ログインユーザーのみ）
     */
    public function authorize(): bool
    {
        //403エラー画面が出るのを防ぐため、ここは一律で true にする
        return true;
    }

    /**
     * バリデーションルール（FN020: 入力必須、最大文字数255）
     */
    public function rules(): array
    {
        return [
            'comment' => 'required|string|max:255', // content から comment に変更
        ];
    }

    /**
     * エラーメッセージのカスタム
     */
    public function messages(): array
    {
        return [
            'comment.required' => 'コメントを入力してください',
            'comment.max' => 'コメントは255文字以内で入力してください',
        ];
    }
}
