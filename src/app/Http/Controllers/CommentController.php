<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest; 
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * コメント送信機能(FN020)
     */
    public function storeComment(CommentRequest $request, $id)
    {
        // コメントデータの保存
        $comment = new Comment();
        $comment->item_id = $id;
        $comment->user_id = Auth::id(); // ログインユーザーのID
        $comment->comment = $request->comment;
        $comment->save();

        // 詳細画面へリダイレクト
        return redirect()->route('items.show', ['id' => $id]);
    }
}
