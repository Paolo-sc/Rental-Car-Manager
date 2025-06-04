<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VehicleInspection extends Model
{
    use HasFactory;

    protected $table = 'vehicle_inspections';

    protected $fillable = [
        'rental_contract_id',
        'inspection_type',
        'vehicle_mileage',
        'fuel_level',
        'exterior_condition',
        'interior_condition',
        'damage_map_data',
        'notes',
        'inspected_by',
    ];

    protected $casts = [
        'rental_contract_id' => 'integer',
        'vehicle_mileage'    => 'integer',
        'damage_map_data'    => 'array',
        'inspected_by'       => 'integer',
    ];

    /**
     * Contratto di noleggio a cui questa ispezione appartiene.
     */
    public function rentalContract(): BelongsTo
    {
        return $this->belongsTo(RentalContract::class, 'rental_contract_id');
    }

    /**
     * Utente che ha effettuato l’ispezione.
     */
    public function inspectedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspected_by');
    }
}