<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    //

    public function post(Request $request)
    {
        $data = $request->all();

        $comment = new Comment();
        $comment->comment = $data['comment'];
        $comment->discussion_id = $data['id'];
        $comment->user_id = Auth::user()->id;
        $comment->save();

        return redirect()->back()->with('status', 'Comment posted successfully');
    }

    public function destory(string $id)
    {

        Comment::find($id)->delete();
        return redirect()->back()->with('status', 'Comment deleted successfully');
    }
}
