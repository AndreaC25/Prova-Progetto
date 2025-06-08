<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Fumetto extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'issue_number',
        'publication_year',
        'cover_image',
        'plot',
        'magazine_id',
        'is_published',
        'published_at'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'publication_year' => 'integer'
    ];

    /**
     * Relazione con l'utente (fumettista)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relazione con la rivista
     */
    public function magazine()
    {
        return $this->belongsTo(Magazine::class);
    }

    /**
     * Relazione many-to-many con le categorie
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'fumetto_category');
    }

    /**
     * Accessor per l'URL dell'immagine di copertina
     */
    public function getCoverImageUrlAttribute()
    {
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }

        return asset('images/default-cover.png');
    }

    /**
     * Scope per fumetti pubblicati
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope per cercare fumetti
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('plot', 'like', "%{$search}%")
              ->orWhereHas('user', function ($userQuery) use ($search) {
                  $userQuery->where('name', 'like', "%{$search}%");
              });
        });
    }

    /**
     * Scope per filtrare per categoria
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->whereHas('categories', function ($q) use ($categoryId) {
            $q->where('categories.id', $categoryId);
        });
    }

    /**
     * Scope per filtrare per anno
     */
    public function scopeByYear($query, $year)
    {
        return $query->where('publication_year', $year);
    }

    /**
     * Scope per filtrare per rivista
     */
    public function scopeByMagazine($query, $magazineId)
    {
        return $query->where('magazine_id', $magazineId);
    }

    /**
     * Imposta automaticamente published_at quando is_published diventa true
     */
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($fumetto) {
            if ($fumetto->is_published && $fumetto->getOriginal('is_published') === false) {
                $fumetto->published_at = now();
            } elseif (!$fumetto->is_published) {
                $fumetto->published_at = null;
            }
        });

        static::creating(function ($fumetto) {
            if ($fumetto->is_published && !$fumetto->published_at) {
                $fumetto->published_at = now();
            }
        });
    }

    /**
     * Elimina l'immagine di copertina quando il fumetto viene eliminato
     */
    protected static function booted()
    {
        static::deleting(function ($fumetto) {
            if ($fumetto->cover_image && Storage::disk('public')->exists($fumetto->cover_image)) {
                Storage::disk('public')->delete($fumetto->cover_image);
            }
        });
    }
}
