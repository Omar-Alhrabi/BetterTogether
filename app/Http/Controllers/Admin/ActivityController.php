<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    /**
     * عرض قائمة الأنشطة
     */
    public function index()
    {
        $activities = Activity::with(['creator', 'category'])->latest()->paginate(10);
        return view('admin.activities.index', compact('activities'));
    }

    /**
     * عرض نموذج إنشاء نشاط جديد
     */
    public function create()
    {
        $categories = Category::all();
        $types = ['join', 'donate', 'both'];
        $statuses = ['upcoming', 'done', 'cancelled'];
        return view('admin.activities.create', compact('categories', 'types', 'statuses'));
    }

    /**
     * تخزين نشاط جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|in:join,donate,both',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'status' => 'required|in:upcoming,done,cancelled',
            'category_id' => 'required|exists:categories,id',
            'donation_goal' => 'nullable|numeric|min:0',
        ]);

        // معالجة الصورة إذا تم تحميلها
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('activity-images', 'public');
        }

        Activity::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image' => $imagePath,
            'type' => $validated['type'],
            'location' => $validated['location'],
            'date' => $validated['date'],
            'status' => $validated['status'],
            'category_id' => $validated['category_id'],
            'created_by' => \Illuminate\Support\Facades\Auth::check() ? \Illuminate\Support\Facades\Auth::id() : null,
            'donation_goal' => $validated['donation_goal'],
        ]);

        return redirect()->route('activities.index')
            ->with('success', 'تم إنشاء النشاط بنجاح');
    }

    /**
     * عرض معلومات نشاط محدد
     */
    public function show(Activity $activity)
    {
        $activity->load(['creator', 'category', 'participants']);
        return view('admin.activities.show', compact('activity'));
    }

    /**
     * عرض نموذج تعديل نشاط
     */
    public function edit(Activity $activity)
    {
        $categories = Category::all();
        $types = ['join', 'donate', 'both'];
        $statuses = ['upcoming', 'done', 'cancelled'];
        return view('admin.activities.edit', compact('activity', 'categories', 'types', 'statuses'));
    }

    /**
     * تحديث معلومات نشاط في قاعدة البيانات
     */
    public function update(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|in:join,donate,both',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'status' => 'required|in:upcoming,done,cancelled',
            'category_id' => 'required|exists:categories,id',
            'donation_goal' => 'nullable|numeric|min:0',
        ]);

        $activityData = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'location' => $validated['location'],
            'date' => $validated['date'],
            'status' => $validated['status'],
            'category_id' => $validated['category_id'],
            'donation_goal' => $validated['donation_goal'],
        ];

        // معالجة الصورة إذا تم تحميلها
        if ($request->hasFile('image')) {
            // حذف الصورة القديمة إذا كانت موجودة
            if ($activity->image) {
                Storage::disk('public')->delete($activity->image);
            }
            
            $activityData['image'] = $request->file('image')->store('activity-images', 'public');
        }

        $activity->update($activityData);

        return redirect()->route('activities.index')
            ->with('success', 'تم تحديث النشاط بنجاح');
    }

    /**
     * حذف نشاط من قاعدة البيانات
     */
    public function destroy(Activity $activity)
    {
        // حذف الصورة إذا كانت موجودة
        if ($activity->image) {
            Storage::disk('public')->delete($activity->image);
        }
        
        $activity->delete();

        return redirect()->route('activities.index')
            ->with('success', 'تم حذف النشاط بنجاح');
    }
}