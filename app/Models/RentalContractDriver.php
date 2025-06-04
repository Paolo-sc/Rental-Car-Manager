<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RentalContractDriver extends Model
{
    use HasFactory;

    protected $table = 'rental_contract_drivers';
    public $incrementing = false; // chiave composta

    protected $fillable = [
        'rental_contract_id',
        'driver_id',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'rental_contract_id' => 'integer',
        'driver_id'          => 'integer',
        'created_by'         => 'integer',
    ];

    /**
     * Il contratto cui questo driver è associato.
     */
    public function rentalContract(): BelongsTo
    {
        return $this->belongsTo(RentalContract::class, 'rental_contract_id');
    }

    /**
     * Il driver aggiuntivo.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    /**
     * Utente che ha creato questa riga pivot.
     */
    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}