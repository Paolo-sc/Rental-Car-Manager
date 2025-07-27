<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invitation extends Model
{
    use HasFactory;

    protected $table = 'invitations';

    /*Gli attributi che si possono assegnare in massa.*/
    protected $fillable = [
        'email',
        'token',
        'used',
        'created_by',
        'expires_at',
    ];

    /*I cast automatici per i campi data/boolean */
    protected $casts = [
        'used' => 'boolean',
        'created_by' => 'integer',
        'expires_at' => 'datetime',
    ];

    /*Gli attributi da nascondere in JSON/serializzazione */
    protected $hidden = [
        'token',
        'created_at',
        'updated_at',
    ];

    /**
     * Relazione con il modello User per l'utente che ha creato l'invito.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Controlla se l'invito è scaduto
     */
    public function isExpired(): bool
    {
        return $this->expires_at < now();
    }
    
    /**
     * Controlla se l'invito è valido (non usato e non scaduto)
     */
    public function isValid(): bool
    {
        return !$this->used && !$this->isExpired();
    }

    /**
     * Scope per inviti validi
     */
    public function scopeValid($query)
    {
        return $query->where('used', false)
                    ->where('expires_at', '>', now());
    }

    /**
     * Scope per inviti scaduti
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }
}
