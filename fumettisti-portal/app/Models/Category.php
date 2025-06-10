<?php

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

    /**
     * Relazione many-to-many con i fumetti
     */
    public function fumetti()
    {
        return $this->belongsToMany(Fumetto::class, 'fumetto_categories');
    }

    /**
     * Solo fumetti pubblicati
     */
    public function publishedFumetti()
    {
        return $this->belongsToMany(Fumetto::class, 'fumetto_categories')
                    ->where('is_published', true);
    }

    /**
     * Scope per categorie attive
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Genera automaticamente lo slug dal nome
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Scope per cercare per nome
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    /**
     * Usa slug per le route
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
