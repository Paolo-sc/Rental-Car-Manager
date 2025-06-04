<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdditionalCharge extends Model
{
    use HasFactory;

    protected $table = 'additional_charges';

    protected $fillable = [
        'rental_contract_id',
        'charge_type',
        'description',
        'amount',
        'quantity',
        'unit_price',
        'added_by',
    ];

    protected $casts = [
        'rental_contract_id' => 'integer',
        'amount'             => 'decimal:2',
        'quantity'           => 'decimal:2',
        'unit_price'         => 'decimal:2',
        'added_by'           => 'integer',
    ];

    /**
     * Contratto a cui appartiene questa spesa.
     */
    public function rentalContract(): BelongsTo
    {
        return $this->belongsTo(RentalContract::class, 'rental_contract_id');
    }

    /**
     * Utente che ha aggiunto la spesa.
     */
    public function addedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}