<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $subject
 * @property string $message
 * @property string $status
 * @property \Carbon\Carbon|null $read_at
 * @property \Carbon\Carbon|null $replied_at
 * @property string|null $admin_notes
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Contact extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status',
        'read_at',
        'replied_at',
        'admin_notes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'read_at' => 'datetime',
        'replied_at' => 'datetime'
    ];

    /**
     * Scope per messaggi non letti
     */
    public function scopeUnread($query)
    {
        return $query->where('status', 'nuovo');
    }

    /**
     * Scope per messaggi letti
     */
    public function scopeRead($query)
    {
        return $query->where('status', 'letto');
    }

    /**
     * Scope per messaggi con risposta
     */
    public function scopeReplied($query)
    {
        return $query->where('status', 'risposto');
    }

    /**
     * Marca il messaggio come letto
     */
    public function markAsRead()
    {
        $this->update([
            'status' => 'letto',
            'read_at' => now()
        ]);
    }

    /**
     * Marca il messaggio come risposto
     */
    public function markAsReplied($adminNotes = null)
    {
        $this->update([
            'status' => 'risposto',
            'replied_at' => now(),
            'admin_notes' => $adminNotes
        ]);
    }

    /**
     * Accessor per il badge di status
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'nuovo' => 'bg-primary',
            'letto' => 'bg-warning',
            'risposto' => 'bg-success'
        ];

        return $badges[$this->status] ?? 'bg-secondary';
    }
}
