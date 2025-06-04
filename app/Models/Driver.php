<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Driver extends Model
{
    use HasFactory;

    protected $table = 'drivers';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'driving_license_number',
        'driving_license_issue_place',
        'driving_license_issue_date',
        'driving_license_expires_at',
        'tax_code',
        'birth_date',
        'birth_place',
        'address',
        'city',
        'postal_code',
        'country',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'driving_license_issue_date'  => 'date',
        'driving_license_expires_at'  => 'date',
        'birth_date'                  => 'date',
        'created_by'                  => 'integer',
    ];

    /**
     * Contratti in cui il driver è principale.
     */
    public function mainRentalContracts(): HasMany
    {
        return $this->hasMany(RentalContract::class, 'main_driver_id');
    }

    /**
     * Contratti in cui il driver è aggiuntivo.
     */
    public function additionalRentalContracts(): HasMany
    {
        return $this->hasMany(RentalContractDriver::class, 'driver_id');
    }

    /**
     * Documenti associati al driver.
     */
    public function personDocuments(): HasMany
    {
        return $this->hasMany(PersonDocument::class, 'driver_id');
    }
}