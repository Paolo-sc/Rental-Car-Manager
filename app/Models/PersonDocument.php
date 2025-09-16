<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonDocument extends Model
{
    use HasFactory;

    protected $table = 'person_documents';

    protected $fillable = [
        'customer_id',
        'driver_id',
        'document_type',
        'id_document_number',
        'drive_file_id',
        'drive_file_url',
        'expiry_date',
        'notes',
        'uploaded_by',
    ];

    protected $casts = [
        'customer_id'  => 'integer',
        'driver_id'    => 'integer',
        'expiry_date'  => 'date:d/m/Y',
        'uploaded_by'  => 'integer',
    ];

    /**
     * Documento associato a un cliente.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Documento associato a un driver.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    /**
     * Utente che ha caricato il documento.
     */
    public function uploadedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}