<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;


    protected $fillable = [
        'content',
        'user_id',
    ];
    // هترجعلبي في الريسبونس liked اضافي
    protected $appends = ['liked'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }
    //  هل اليوز عامل لايك او لا
    public function getLikedAttribute(): bool
    {
        return (bool) $this->likes()->where('post_id', $this->id)->where('user_id', auth()->user()->id)->exists();
    }
}
