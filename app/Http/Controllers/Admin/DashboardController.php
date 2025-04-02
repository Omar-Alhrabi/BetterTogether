<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Activity;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\Donation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * عرض لوحة التحكم الرئيسية
     */
    public function index()
    {
        // جمع الإحصائيات لعرضها في لوحة التحكم
        $stats = [
            'users_count' => User::count(),
            'activities_count' => Activity::count(),
            'posts_count' => Post::count(),
            'comments_count' => PostComment::count(),
            'donations_count' => Donation::count(),
            'total_donations' => Donation::sum('amount'),
            'recent_users' => User::latest()->take(5)->get(),
            'recent_activities' => Activity::with('category')->latest()->take(5)->get(),
            'recent_posts' => Post::with('user')->latest()->take(5)->get(),
            'upcoming_activities' => Activity::where('status', 'upcoming')->count(),
            'done_activities' => Activity::where('status', 'done')->count(),
        ];
        
        return view('admin.dashboard', compact('stats'));
    }
}