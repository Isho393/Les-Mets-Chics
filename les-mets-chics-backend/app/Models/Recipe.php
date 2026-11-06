<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'description',
        'main_image_url',
        'video_url',
        'prep_time',
        'cook_time',
        'servings',
        'ingredients',
        'steps',
        'status',
    ];

    protected $casts = [
        'ingredients' => 'array',
        'steps' => 'array',
        'prep_time' => 'integer',
        'cook_time' => 'integer',
        'servings' => 'integer',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function approvedComments()
    {
        return $this->hasMany(Comment::class)->where('is_approved', true);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }

    public function likesCount()
    {
        return $this->likes()->count();
    }
}
