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

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relazione con il profilo utente
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Relazione con i fumetti creati dall'utente
     */
    public function fumetti()
    {
        return $this->hasMany(Fumetto::class);
    }

    /**
     * Crea automaticamente un profilo quando viene creato un utente
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            $user->profile()->create([]);
        });
    }
}
