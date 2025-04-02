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
     * عرض قائمة المنشورات
     */
    public function index()
    {
        $posts = Post::with(['user', 'activity'])->latest()->paginate(10);
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * عرض نموذج إنشاء منشور جديد
     */
    public function create()
    {
        $activities = Activity::all();
        return view('admin.posts.create', compact('activities'));
    }

    /**
     * تخزين منشور جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'activity_id' => 'nullable|exists:activities,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // معالجة الصورة إذا تم تحميلها
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('post-images', 'public');
        }

        Post::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'activity_id' => $validated['activity_id'],
            'image' => $imagePath,
            'user_id' => Auth::check() ? Auth::id() : null,
        ]);

        return redirect()->route('posts.index')
            ->with('success', 'تم إنشاء المنشور بنجاح');
    }

    /**
     * عرض معلومات منشور محدد
     */
    public function show(Post $post)
    {
        $post->load(['user', 'activity', 'comments.user', 'likes']);
        return view('admin.posts.show', compact('post'));
    }

    /**
     * عرض نموذج تعديل منشور
     */
    public function edit(Post $post)
    {
        $activities = Activity::all();
        return view('admin.posts.edit', compact('post', 'activities'));
    }

    /**
     * تحديث معلومات منشور في قاعدة البيانات
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'activity_id' => 'nullable|exists:activities,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $postData = [
            'title' => $validated['title'],
            'content' => $validated['content'],
            'activity_id' => $validated['activity_id'],
        ];

        // معالجة الصورة إذا تم تحميلها
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            
            $postData['image'] = $request->file('image')->store('post-images', 'public');
        }

        $post->update($postData);

        return redirect()->route('posts.index')
            ->with('success', 'تم تحديث المنشور بنجاح');
    }

    /**
     * حذف منشور من قاعدة البيانات
     */
    public function destroy(Post $post)
    {
        // حذف الصورة إذا كانت موجودة
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        
        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'تم حذف المنشور بنجاح');
    }

    /**
     * عرض تعليقات المنشور
     */
    public function comments(Post $post)
    {
        $comments = $post->comments()->with('user')->paginate(15);
        return view('admin.posts.comments', compact('post', 'comments'));
    }

    /**
     * حذف تعليق
     */
    public function deleteComment($id)
    {
        $comment = PostComment::findOrFail($id);
        $comment->delete();

        return redirect()->back()->with('success', 'تم حذف التعليق بنجاح');
    }
}