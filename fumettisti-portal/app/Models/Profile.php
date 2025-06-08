<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'company_address',
        'profile_image',
        'description'
    ];

    /**
     * Relazione con il modello User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor per l'URL dell'immagine profilo
     */
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }

        return asset('images/default-avatar.png');
    }
}
