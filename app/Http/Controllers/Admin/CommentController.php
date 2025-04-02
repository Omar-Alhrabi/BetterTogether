<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostComment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * عرض قائمة جميع التعليقات
     */
    public function index()
    {
        $comments = PostComment::with(['post', 'user'])->latest()->paginate(15);
        return view('admin.comments.index', compact('comments'));
    }

    /**
     * عرض نموذج إنشاء تعليق جديد
     */
    public function create()
    {
        $posts = Post::all();
        return view('admin.comments.create', compact('posts'));
    }

    /**
     * تخزين تعليق جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'comment' => 'required|string',
        ]);

        PostComment::create([
            'post_id' => $validated['post_id'],
            'user_id' => optional(Auth::user())->id,
            'comment' => $validated['comment'],
        ]);

        return redirect()->route('comments.index')
            ->with('success', 'تم إضافة التعليق بنجاح');
    }

    /**
     * عرض تعليق محدد
     */
    public function show(PostComment $comment)
    {
        $comment->load(['post', 'user']);
        return view('admin.comments.show', compact('comment'));
    }

    /**
     * عرض نموذج تعديل تعليق
     */
    public function edit(PostComment $comment)
    {
        $posts = Post::all();
        return view('admin.comments.edit', compact('comment', 'posts'));
    }

    /**
     * تحديث تعليق في قاعدة البيانات
     */
    public function update(Request $request, PostComment $comment)
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'comment' => 'required|string',
        ]);

        $comment->update([
            'post_id' => $validated['post_id'],
            'comment' => $validated['comment'],
        ]);

        return redirect()->route('comments.index')
            ->with('success', 'تم تحديث التعليق بنجاح');
    }

    /**
     * حذف تعليق من قاعدة البيانات
     */
    public function destroy(PostComment $comment)
    {
        $comment->delete();
        
        return redirect()->route('comments.index')
            ->with('success', 'تم حذف التعليق بنجاح');
    }
}