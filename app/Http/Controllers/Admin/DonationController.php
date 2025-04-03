<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Activity;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    /**
     * Display a listing of donations
     */
    public function index()
    {
        $donations = Donation::with(['user', 'activity'])->latest()->paginate(10);
        $totalDonations = Donation::sum('amount');
        
        // Since there's no status column, we'll simplify the statistics
        // or we can modify these to use other existing columns
        $totalCount = Donation::count();
        $totalDonors = Donation::distinct('user_id')->count('user_id');
        $avgDonation = Donation::avg('amount') ?? 0;
        
        return view('admin.donations.index', compact(
            'donations', 
            'totalDonations', 
            'totalCount',
            'totalDonors',
            'avgDonation'
        ));
    }

    /**
     * Display the specified donation
     */
    public function show(Donation $donation)
    {
        $donation->load(['user', 'activity']);
        
        // Get related donations (by same user or for same activity)
        $relatedDonations = Donation::where(function($query) use ($donation) {
                $query->where('user_id', $donation->user_id)
                      ->orWhere('activity_id', $donation->activity_id);
            })
            ->where('id', '!=', $donation->id)
            ->latest()
            ->limit(5)
            ->get();
            
        return view('admin.donations.show', compact('donation', 'relatedDonations'));
    }

    /**
     * Display donation statistics
     */
    public function statistics()
    {
        $totalDonations = Donation::sum('amount');
        
        // Get activities with donation counts and sums
        $donationsByActivity = Activity::with(['donations'])
            ->withCount('donations')
            ->withSum('donations as total_donations', 'amount')
            ->get();
        
        // Add percentage calculation for each activity
        foreach ($donationsByActivity as $activity) {
            if (isset($activity->donation_goal) && $activity->donation_goal > 0) {
                $activity->progress_percentage = min(($activity->total_donations / $activity->donation_goal) * 100, 100);
            } else {
                $activity->progress_percentage = 0;
            }
        }
        
        return view('admin.donations.statistics', compact('totalDonations', 'donationsByActivity'));
    }
}