<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    /**
     * Display a listing of activities
     */
    public function index()
    {
        $activities = Activity::with(['creator', 'category'])->latest()->paginate(10);
        return view('admin.activities.index', compact('activities'));
    }

    /**
     * Show the form for creating a new activity
     */
    public function create()
    {
        $categories = Category::all();
        $types = ['join', 'donate', 'both'];
        $statuses = ['upcoming', 'done', 'cancelled'];
        return view('admin.activities.create', compact('categories', 'types', 'statuses'));
    }

    /**
     * Store a newly created activity in storage
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

        // Process uploaded image if provided
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('activity-images', 'public');
        }

        // Create new activity with validated data
        Activity::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image' => $imagePath,
            'type' => $validated['type'],
            'location' => $validated['location'],
            'date' => $validated['date'],
            'status' => $validated['status'],
            'category_id' => $validated['category_id'],
            'created_by' => Auth::id(),
            'donation_goal' => $validated['donation_goal'],
        ]);

        return redirect()->route('activities.index')
            ->with('success', 'Activity created successfully');
    }

    /**
     * Display the specified activity
     */
    public function show(Activity $activity)
    {
        $activity->load(['creator', 'category', 'participants']);
        return view('admin.activities.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified activity
     */
    public function edit(Activity $activity)
    {
        $categories = Category::all();
        $types = ['join', 'donate', 'both'];
        $statuses = ['upcoming', 'done', 'cancelled'];
        return view('admin.activities.edit', compact('activity', 'categories', 'types', 'statuses'));
    }

    /**
     * Update the specified activity in storage
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

        // Handle image deletion if requested
        if ($request->has('delete_image') && $activity->image) {
            Storage::disk('public')->delete($activity->image);
            $activityData['image'] = null;
        }
        
        // Handle image upload if provided
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($activity->image) {
                Storage::disk('public')->delete($activity->image);
            }
            
            $activityData['image'] = $request->file('image')->store('activity-images', 'public');
        }

        $activity->update($activityData);

        return redirect()->route('activities.index')
            ->with('success', 'Activity updated successfully');
    }

    /**
     * Remove the specified activity from storage
     */
    public function destroy(Activity $activity)
    {
        // Delete image if exists
        if ($activity->image) {
            Storage::disk('public')->delete($activity->image);
        }
        
        $activity->delete();

        return redirect()->route('activities.index')
            ->with('success', 'Activity deleted successfully');
    }
}