<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'customer_type',
        'first_name',
        'last_name',
        'company_name',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'country',
        'tax_code',
        'vat_number',
        'id_document_number',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'created_by' => 'integer',
    ];

    /**
     * Contratti firmati da questo cliente.
     */
    public function rentalContracts(): HasMany
    {
        return $this->hasMany(RentalContract::class, 'customer_id');
    }

    /**
     * Documenti associati al cliente (solo individuali).
     */
    public function personDocuments(): HasMany
    {
        return $this->hasMany(PersonDocument::class, 'customer_id');
    }
}