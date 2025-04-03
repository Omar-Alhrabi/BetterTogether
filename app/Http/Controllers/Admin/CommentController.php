<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostComment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of comments
     */
    public function index()
    {
        // Use eager loading to prevent N+1 query problem
        $comments = PostComment::with(['post', 'user'])->latest()->paginate(15);
        return view('admin.comments.index', compact('comments'));
    }

    /**
     * Display the specified comment
     */
    public function show(PostComment $comment)
    {
        // Use eager loading to prevent N+1 query problem
        $comment->load(['post', 'user']);
        return view('admin.comments.show', compact('comment'));
    }

    /**
     * Remove the specified comment from database
     */
    public function destroy(PostComment $comment)
    {
        $comment->delete();
        
        return redirect()->route('comments.index')
            ->with('success', 'Comment deleted successfully');
    }
}