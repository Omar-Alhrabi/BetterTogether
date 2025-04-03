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
     * Check if the activity is upcoming
     */
    public function isUpcoming()
    {
        return $this->status === 'upcoming';
    }

    /**
     * Check if the activity is done
     */
    public function isDone()
    {
        return $this->status === 'done';
    }

    /**
     * Check if the activity is cancelled
     */
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    // Relationships

    /**
     * Get the user who created the activity
     */
    public function organizer()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Alias for organizer() to maintain compatibility with existing code
     */
    public function creator()
    {
        return $this->organizer();
    }

    /**
     * Get the users who are participating in the activity
     */
    public function participants()
    {
        return $this->belongsToMany(User::class, 'activity_user')
            ->withTimestamps()
            ->withPivot('joined_at');
    }

    /**
     * Get the category associated with the activity
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the donations associated with the activity
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Get the posts associated with the activity
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the total donations for this activity
     */
    public function getTotalDonationsAttribute()
    {
        return $this->donations()->sum('amount');
    }

    /**
     * Get the donation percentage (progress towards goal)
     */
    public function getDonationPercentageAttribute()
    {
        if (!$this->donation_goal || $this->donation_goal <= 0) {
            return 0;
        }
        
        return min(100, round(($this->total_donations / $this->donation_goal) * 100));
    }
}