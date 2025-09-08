<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RentalContract extends Model
{
    use HasFactory;

    protected $table = 'rental_contracts';

    protected $fillable = [
        'contract_number',
        'booking_code',
        'customer_id',
        'main_driver_id',
        'vehicle_id',
        'start_date',
        'end_date',
        'pickup_time',
        'return_time',
        'pickup_location',
        'return_location',
        'daily_rate',
        'total_days',
        'subtotal',
        'discount_amount',
        'tax_rate',
        'tax_amount',
        'total_amount',
        'deposit_amount',
        'deposit_payment_method',
        'total_paid',
        'status',
        'payment_received',
        'payment_date',
        'payment_method',
        'payment_notes',
        'km_included_type',
        'km_included_value',
        'franchise_theft_fire',
        'deductible_damage',
        'deductible_rca',
        'max_passengers',
        'special_conditions',
        'customer_signature_required',
        'customer_signature_obtained',
        'signature_date',
        'notes',
        'contract_pdf_drive_file_id',
        'contract_pdf_drive_file_url',
        'created_by',
    ];

    protected $casts = [
        'start_date'                 => 'date:Y/m/d',
        'end_date'                   => 'date:Y/m/d',
        'pickup_time'                => 'string',
        'return_time'                => 'string',
        'daily_rate'                 => 'decimal:2',
        'subtotal'                   => 'decimal:2',
        'discount_amount'            => 'decimal:2',
        'tax_rate'                   => 'decimal:2',
        'tax_amount'                 => 'decimal:2',
        'total_amount'               => 'decimal:2',
        'deposit_amount'             => 'decimal:2',
        'total_paid'                 => 'decimal:2',
        'payment_received'           => 'boolean',
        'payment_date'               => 'date:Y/m/d',
        'km_included_value'          => 'integer',
        'franchise_theft_fire'       => 'decimal:2',
        'deductible_damage'          => 'decimal:2',
        'deductible_rca'             => 'decimal:2',
        'max_passengers'             => 'integer',
        'customer_signature_required'=> 'boolean',
        'customer_signature_obtained'=> 'boolean',
        'signature_date'             => 'datetime',
        'created_by'                 => 'integer',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function mainDriver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'main_driver_id');
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function additionalDrivers(): HasMany
    {
        return $this->hasMany(RentalContractDriver::class, 'rental_contract_id');
    }

    public function vehicleInspections(): HasMany
    {
        return $this->hasMany(VehicleInspection::class, 'rental_contract_id');
    }

    public function additionalCharges(): HasMany
    {
        return $this->hasMany(AdditionalCharge::class, 'rental_contract_id');
    }
}
