<?php
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
        'phone',
        'company_address',
        'avatar',
        'bio',
        'birth_date',
        'website',
        'social_links'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'social_links' => 'array'
    ];

    /**
     * Relazione con l'utente
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor per l'URL dell'avatar
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            // Se è un URL esterno (es. da social login)
            if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
                return $this->avatar;
            }
            // Se è un file locale
            return asset('storage/' . $this->avatar);
        }

        // Avatar di default basato sulle iniziali
        return $this->getDefaultAvatarUrl();
    }

    /**
     * Genera URL avatar di default
     */
    public function getDefaultAvatarUrl()
    {
        $name = $this->user->name ?? 'User';
        $initials = collect(explode(' ', $name))->map(function ($word) {
            return strtoupper(substr($word, 0, 1));
        })->take(2)->implode('');

        // Usa un servizio di avatar come UI Avatars
        return "https://ui-avatars.com/api/?name={$initials}&size=200&background=6366f1&color=ffffff";
    }

    /**
     * Accessor per il numero di telefono formattato
     */
    public function getFormattedPhoneAttribute()
    {
        if (!$this->phone) return null;

        // Formatta il numero italiano
        if (preg_match('/^(\+39)?([0-9]{10})$/', $this->phone, $matches)) {
            $number = $matches[2];
            return '+39 ' . substr($number, 0, 3) . ' ' . substr($number, 3, 3) . ' ' . substr($number, 6);
        }

        return $this->phone;
    }

    /**
     * Accessor per l'età
     */
    public function getAgeAttribute()
    {
        if (!$this->birth_date) return null;
        return $this->birth_date->age;
    }

    /**
     * Verifica se il profilo è completo
     */
    public function getIsCompleteAttribute()
    {
        return !empty($this->phone) &&
               !empty($this->bio) &&
               !empty($this->avatar);
    }

    /**
     * Percentuale di completamento profilo
     */
    public function getCompletionPercentageAttribute()
    {
        $fields = ['phone', 'bio', 'avatar', 'birth_date', 'company_address'];
        $completed = 0;

        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $completed++;
            }
        }

        return round(($completed / count($fields)) * 100);
    }

    /**
     * Scope per profili completi
     */
    public function scopeComplete($query)
    {
        return $query->whereNotNull('phone')
                    ->whereNotNull('bio')
                    ->whereNotNull('avatar');
    }

    /**
     * Scope per ricerca per telefono
     */
    public function scopeByPhone($query, $phone)
    {
        return $query->where('phone', $phone);
    }
}
