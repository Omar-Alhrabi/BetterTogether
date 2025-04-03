<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Activity;
use App\Models\PostComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the posts
     */
    public function index()
    {
        $posts = Post::with(['user', 'activity'])->latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Display the specified post
     */
    public function show(Post $post)
    {
        $post->load(['user', 'activity', 'comments.user', 'likes']);
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Remove the specified post from database
     */
    public function destroy(Post $post)
    {
        // Delete the image if it exists
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        
        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully');
    }

    /**
     * Display post comments
     */
    public function comments(Post $post)
    {
        $comments = $post->comments()->with('user')->paginate(15);
        return view('admin.posts.comments', compact('post', 'comments'));
    }

    /**
     * Delete a comment
     */
    public function deleteComment($id)
    {
        $comment = PostComment::findOrFail($id);
        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully');
    }
}