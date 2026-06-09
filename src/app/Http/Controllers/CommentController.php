<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest; 
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function comment(CommentRequest $request, $item_id)
    {
        $comment = new Comment();
        $comment->item_id = $item_id;
        $comment->user_id = Auth::id();
        $comment->comment = $request->comment;
        $comment->save();
        return redirect()->route('items.show', ['item_id' => $item_id]);
    }
}
