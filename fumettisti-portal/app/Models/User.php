<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function fumetti()
    {
        return $this->hasMany(Fumetto::class);
    }

    public function publishedFumetti()
    {
        return $this->hasMany(Fumetto::class)->published();
    }

    public function draftFumetti()
    {
        return $this->hasMany(Fumetto::class)->draft();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoriteFumetti()
    {
        return $this->belongsToMany(Fumetto::class, 'favorites');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * Scopes
     */
    public function scopeAdmins($query)
    {
        return $query->where('is_admin', true);
    }

    public function scopeUsers($query)
    {
        return $query->where('is_admin', false);
    }

    public function scopeActive($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function scopeWithPublishedFumetti($query)
    {
        return $query->whereHas('fumetti', function($q) {
            $q->published();
        });
    }

    /**
     * Accessors
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->profile && $this->profile->avatar) {
            return $this->profile->avatar_url;
        }

        // Generate Gravatar URL as fallback
        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=identicon&s=200";
    }

    public function getDisplayNameAttribute()
    {
        return $this->profile->display_name ?? $this->name;
    }

    public function getBioAttribute()
    {
        return $this->profile->bio ?? null;
    }

    public function getSocialLinksAttribute()
    {
        return $this->profile->social_links ?? [];
    }

    /**
     * Methods
     */
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    public function hasProfile(): bool
    {
        return $this->profile !== null;
    }

    public function createProfile(array $data = []): Profile
    {
        return $this->profile()->create($data);
    }

    public function getOrCreateProfile(): Profile
    {
        return $this->profile ?? $this->createProfile();
    }

    public function hasFavorited(Fumetto $fumetto): bool
    {
        return $this->favorites()->where('fumetto_id', $fumetto->id)->exists();
    }

    public function hasReviewed(Fumetto $fumetto): bool
    {
        return $this->reviews()->where('fumetto_id', $fumetto->id)->exists();
    }

    public function getReviewFor(Fumetto $fumetto): ?Review
    {
        return $this->reviews()->where('fumetto_id', $fumetto->id)->first();
    }

    public function toggleFavorite(Fumetto $fumetto): bool
    {
        $favorite = $this->favorites()->where('fumetto_id', $fumetto->id)->first();

        if ($favorite) {
            $favorite->delete();
            return false; // Removed from favorites
        } else {
            $this->favorites()->create(['fumetto_id' => $fumetto->id]);
            return true; // Added to favorites
        }
    }

    public function getStatsAttribute(): array
    {
        return [
            'total_fumetti' => $this->fumetti()->count(),
            'published_fumetti' => $this->publishedFumetti()->count(),
            'draft_fumetti' => $this->draftFumetti()->count(),
            'total_reviews_received' => $this->fumetti()->withCount('reviews')->get()->sum('reviews_count'),
            'total_favorites_received' => $this->fumetti()->withCount('favorites')->get()->sum('favorites_count'),
            'reviews_given' => $this->reviews()->count(),
            'favorites_given' => $this->favorites()->count(),
            'average_rating' => $this->fumetti()->published()->withAvg('approvedReviews', 'rating')->get()->avg('approved_reviews_avg_rating') ?? 0,
            'member_since' => $this->created_at
        ];
    }

    public function canEdit(Fumetto $fumetto): bool
    {
        return $this->id === $fumetto->user_id || $this->isAdmin();
    }

    public function canDelete(Fumetto $fumetto): bool
    {
        return $this->id === $fumetto->user_id || $this->isAdmin();
    }

    public function canReview(Fumetto $fumetto): bool
    {
        return $this->id !== $fumetto->user_id && !$this->hasReviewed($fumetto);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        // Create profile when user is created
        static::created(function ($user) {
            $user->createProfile();
        });

        // Clean up related data when user is deleted
        static::deleting(function ($user) {
            // Delete user's fumetti (which will trigger fumetto deletion cleanup)
            $user->fumetti()->delete();

            // Delete user's reviews and favorites
            $user->reviews()->delete();
            $user->favorites()->delete();
            $user->contacts()->delete();

            // Delete profile
            if ($user->profile) {
                $user->profile->delete();
            }
        });
    }
}
