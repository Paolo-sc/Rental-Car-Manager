<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory;

    protected $table = 'vehicles';

    protected $fillable = [
        'license_plate',
        'brand',
        'model',
        'year',
        'color',
        'fuel_type',
        'transmission',
        'seats',
        'vin',
        'engine_size',
        'mileage',
        'status',
        'notes',
    ];

    protected $casts = [
        'year'    => 'integer',
        'seats'   => 'integer',
        'mileage' => 'integer',
    ];

    /**
     * Contratti associati al veicolo.
     */
    public function rentalContracts(): HasMany
    {
        return $this->hasMany(RentalContract::class, 'vehicle_id');
    }

    /**
     * Documenti associati al veicolo.
     */
    public function vehicleDocuments(): HasMany
    {
        return $this->hasMany(VehicleDocument::class, 'vehicle_id');
    }

    /**
     * Ispezioni associate al veicolo (attraverso il contratto).
     */
    public function vehicleInspections(): HasMany
    {
        return $this->hasMany(VehicleInspection::class, 'rental_contract_id');
    }
}
