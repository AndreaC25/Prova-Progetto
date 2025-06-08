<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magazine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country',
        'description',
        'website',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Relazione con i fumetti
     */
    public function fumetti()
    {
        return $this->hasMany(Fumetto::class);
    }

    /**
     * Accessor per il nome del paese
     */
    public function getCountryNameAttribute()
    {
        $countries = [
            'IT' => 'Italia',
            'JP' => 'Giappone',
            'US' => 'Stati Uniti',
            'FR' => 'Francia',
            'DE' => 'Germania',
            'ES' => 'Spagna',
            'GB' => 'Regno Unito',
            'CA' => 'Canada',
            'AU' => 'Australia',
            'BR' => 'Brasile',
        ];

        return $countries[$this->country] ?? $this->country;
    }

    /**
     * Scope per riviste attive
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope per cercare per nome
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }
}
