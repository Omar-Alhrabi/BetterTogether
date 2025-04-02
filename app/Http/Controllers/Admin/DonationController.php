<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    /**
     * عرض قائمة التبرعات
     */
    public function index()
    {
        $donations = Donation::with(['user', 'activity'])->latest()->paginate(10);
        $totalDonations = Donation::sum('amount');
        return view('admin.donations.index', compact('donations', 'totalDonations'));
    }

    /**
     * عرض نموذج إنشاء تبرع جديد
     */
    public function create()
    {
        $users = User::all();
        $activities = Activity::whereIn('type', ['donate', 'both'])->get();
        return view('admin.donations.create', compact('users', 'activities'));
    }

    /**
     * تخزين تبرع جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'activity_id' => 'required|exists:activities,id',
            'amount' => 'required|numeric|min:1',
            'donated_at' => 'nullable|date',
        ]);

        $donation = new Donation();
        $donation->user_id = $validated['user_id'];
        $donation->activity_id = $validated['activity_id'];
        $donation->amount = $validated['amount'];
        $donation->donated_at = $validated['donated_at'] ?? now();
        $donation->save();

        return redirect()->route('donations.index')
            ->with('success', 'تم تسجيل التبرع بنجاح');
    }

    /**
     * عرض معلومات تبرع محدد
     */
    public function show(Donation $donation)
    {
        $donation->load(['user', 'activity']);
        return view('admin.donations.show', compact('donation'));
    }

    /**
     * عرض نموذج تعديل تبرع
     */
    public function edit(Donation $donation)
    {
        $users = User::all();
        $activities = Activity::whereIn('type', ['donate', 'both'])->get();
        return view('admin.donations.edit', compact('donation', 'users', 'activities'));
    }

    /**
     * تحديث معلومات تبرع في قاعدة البيانات
     */
    public function update(Request $request, Donation $donation)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'activity_id' => 'required|exists:activities,id',
            'amount' => 'required|numeric|min:1',
            'donated_at' => 'nullable|date',
        ]);

        $donation->update([
            'user_id' => $validated['user_id'],
            'activity_id' => $validated['activity_id'],
            'amount' => $validated['amount'],
            'donated_at' => $validated['donated_at'] ?? now(),
        ]);

        return redirect()->route('donations.index')
            ->with('success', 'تم تحديث التبرع بنجاح');
    }

    /**
     * حذف تبرع من قاعدة البيانات
     */
    public function destroy(Donation $donation)
    {
        $donation->delete();

        return redirect()->route('donations.index')
            ->with('success', 'تم حذف التبرع بنجاح');
    }

    /**
     * عرض إحصائيات التبرعات
     */
    public function statistics()
    {
        $totalDonations = Donation::sum('amount');
        $donationsByActivity = Activity::whereIn('type', ['donate', 'both'])
            ->withCount('donations')
            ->withSum('donations as total_donations', 'amount')
            ->get();
        
        return view('admin.donations.statistics', compact('totalDonations', 'donationsByActivity'));
    }
}