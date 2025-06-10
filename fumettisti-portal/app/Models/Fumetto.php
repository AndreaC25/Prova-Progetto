<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Fumetto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fumetti';

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

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // Relazione con le recensioni approvate
    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
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
        // Se ha un'immagine custom, usala
        if ($this->cover_image) {
            // Se è un URL esterno, restituiscilo direttamente
            if (filter_var($this->cover_image, FILTER_VALIDATE_URL)) {
                return $this->cover_image;
            }
            // Se è un file locale, usa Storage
            return Storage::url($this->cover_image);
        }
        // Altrimenti genera un'immagine placeholder basata sull'ID
        return "https://picsum.photos/300/400?random=" . ($this->id + 100);
    }

    public function getAverageRatingAttribute()
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    /**
     * Genera HTML per le stelle di rating
     */
    public function getStarsHtmlAttribute()
    {
        $rating = $this->average_rating;
        $fullStars = floor($rating);
        $hasHalfStar = ($rating - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);

        $html = '';

        // Stelle piene
        for ($i = 0; $i < $fullStars; $i++) {
            $html .= '<i class="fas fa-star text-warning"></i>';
        }

        // Stella mezza
        if ($hasHalfStar) {
            $html .= '<i class="fas fa-star-half-alt text-warning"></i>';
        }

        // Stelle vuote
        for ($i = 0; $i < $emptyStars; $i++) {
            $html .= '<i class="far fa-star text-warning"></i>';
        }

        return $html;
    }
}
