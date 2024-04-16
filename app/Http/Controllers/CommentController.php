<?php

namespace App\Http\Controllers;
use App\Models\Comment;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Store Comment
    public function store_comment(Request $request){

        // Validate the incoming request data
        $request->validate([
            'commentData' => 'required|string',
        ]);

        $comment = new Comment();
        $comment->comment = $request->commentData;
        $comment->blog_id = $request->blog_idData;
        $comment->user_id = auth()->user()->id;
        $comment->save();

        $comment->load('user');

        // Return a JSON response
        return response()->json(['success' => true, 'comment' => $comment]);

        
    }

    // Store Comment Replies
    public function store_reply(Request $request){

        // Validate the incoming request data
        $request->validate([
            'replyData' => 'required|string',
        ]);

        // Save the comment to the database
        $comment = new Comment();
        $comment->comment = $request->replyData;
        $comment->blog_id = $request->post_idData;
        $comment->user_id = auth()->user()->id;
        $comment->parent_id = $request->parent_idData;
        $comment->save();

        $comment->load('user');

        // Return a JSON response
        return response()->json(['success' => true, 'reply' => $comment]);

        
    }
}
