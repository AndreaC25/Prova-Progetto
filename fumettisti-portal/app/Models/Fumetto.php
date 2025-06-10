<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Fumetto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fumetti'; // Forza il nome tabella

    protected $fillable = [
        'title',
        'plot',
        'issue_number',
        'publication_year',
        'price',
        'cover_image',
        'magazine_id',
        'user_id',
        'is_published',
        'published_at'
    ];

    protected $casts = [
        'publication_year' => 'integer',
        'issue_number' => 'integer',
        'price' => 'decimal:2',
        'is_published' => 'boolean',
        'published_at' => 'datetime'
    ];

    protected $dates = [
        'published_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function magazine()
    {
        return $this->belongsTo(Magazine::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'fumetto_categories');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true)->whereNotNull('published_at');
    }

    public function scopeDraft($query)
    {
        return $query->where('is_published', false);
    }

    // Accessors
    public function getCoverImageUrlAttribute()
    {
        if ($this->cover_image) {
            return Storage::url($this->cover_image);
        }

        return asset('images/default-cover.jpg');
    }

    public function getAverageRatingAttribute()
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }
}
