<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VehicleDocument extends Model
{
    use HasFactory;

    protected $table = 'vehicle_documents';

    protected $fillable = [
        'vehicle_id',
        'document_type',
        'document_name',
        'drive_file_id',
        'drive_file_url',
        'notes',
        'uploaded_by',
    ];

    protected $casts = [
        'vehicle_id'  => 'integer',
        'uploaded_by' => 'integer',
    ];

    /**
     * Veicolo a cui appartiene il documento.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    /**
     * Utente che ha caricato il documento.
     */
    public function uploadedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
