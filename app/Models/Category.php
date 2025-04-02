<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * عدد الأنشطة في هذه الفئة
     */
    public function getActivitiesCountAttribute()
    {
        return $this->activities()->count();
    }

    /**
     * عدد الأنشطة النشطة في هذه الفئة
     */
    public function getActiveActivitiesCountAttribute()
    {
        return $this->activities()->where('status', 'upcoming')->count();
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}