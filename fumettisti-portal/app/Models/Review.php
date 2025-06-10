<?php
// app/Models/Review.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fumetto_id',
        'rating',
        'comment',
        'is_approved'
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fumetto()
    {
        return $this->belongsTo(Fumetto::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }
}

// app/Models/Favorite.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fumetto_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fumetto()
    {
        return $this->belongsTo(Fumetto::class);
    }
}

// app/Models/Profile.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'display_name',
        'bio',
        'avatar',
        'website',
        'social_links',
        'location',
        'birth_date',
        'is_public'
    ];

    protected $casts = [
        'social_links' => 'array',
        'birth_date' => 'date',
        'is_public' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return Storage::url($this->avatar);
        }

        // Fallback to Gravatar
        $hash = md5(strtolower(trim($this->user->email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=identicon&s=200";
    }

    public function getDisplayNameAttribute()
    {
        return $this->attributes['display_name'] ?? $this->user->name;
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($profile) {
            if ($profile->avatar) {
                Storage::disk('public')->delete($profile->avatar);
            }
        });
    }
}

// app/Models/Category.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function fumetti()
    {
        return $this->belongsToMany(Fumetto::class, 'fumetto_categories');
    }

    public function publishedFumetti()
    {
        return $this->belongsToMany(Fumetto::class, 'fumetto_categories')
                    ->where('is_published', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($category) {
            if (!$category->slug) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}

// app/Models/Magazine.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magazine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'website',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function fumetti()
    {
        return $this->hasMany(Fumetto::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

// app/Models/Contact.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'subject',
        'message',
        'status',
        'admin_notes'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAnswered($query)
    {
        return $query->where('status', 'answered');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">In Attesa</span>',
            'answered' => '<span class="badge bg-success">Risposto</span>',
            'closed' => '<span class="badge bg-secondary">Chiuso</span>'
        ];

        return $badges[$this->status] ?? '<span class="badge bg-light">Sconosciuto</span>';
    }
}
