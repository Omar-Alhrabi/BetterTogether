<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_id',
        'amount',
        'donation_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'donation_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * تنسيق المبلغ بالعملة
     */
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2) . ' ر.س';
    }

    //  Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}