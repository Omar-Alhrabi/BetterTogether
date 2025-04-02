<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'image',
        'type',
        'location',
        'date',
        'status',
        'category_id',
        'created_by',
        'donation_goal',
        'max_participants',
    ];

    protected $casts = [
        'date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'donation_goal' => 'decimal:2',
    ];

    /**
     * تحديد ما إذا كان النشاط قادمًا
     */
    public function isUpcoming()
    {
        return $this->status === 'upcoming';
    }

    /**
     * تحديد ما إذا كان النشاط منتهيًا
     */
    public function isDone()
    {
        return $this->status === 'done';
    }

    /**
     * تحديد ما إذا كان النشاط ملغيًا
     */
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    // Relationships

    public function organizer()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'activity_user')
            ->withTimestamps()
            ->withPivot('joined_at');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * إجمالي مبلغ التبرعات لهذا النشاط
     */
    public function getTotalDonationsAttribute()
    {
        return $this->donations()->sum('amount');
    }

    /**
     * نسبة التبرعات المحصلة من الهدف
     */
    public function getDonationPercentageAttribute()
    {
        if (!$this->donation_goal || $this->donation_goal <= 0) {
            return 0;
        }
        
        return min(100, round(($this->total_donations / $this->donation_goal) * 100));
    }
}