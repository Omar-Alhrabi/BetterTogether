<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'activity_id',
        'title',
        'content',
        'image',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * عدد الإعجابات على المنشور
     */
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    /**
     * عدد التعليقات على المنشور
     */
    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }

    /**
     * تحديد ما إذا كان المنشور معجب به من قبل مستخدم معين
     */
    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    /**
     * رابط الصورة الكاملة
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        // صورة افتراضية
        return asset('images/default-post.jpg');
    }

    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class);
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }
}