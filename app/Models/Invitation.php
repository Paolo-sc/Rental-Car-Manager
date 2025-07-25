<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invitation extends Model
{
    use HasFactory;

    protected $table = 'invitations';

    /*Gli attributi che si possono assegnare in massa.*/
    protected $fillable = [
        'email',
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
    public function createdBy(): HasMany
    {
        return $this->hasMany(User::class, 'id', 'created_by');
    }
}
