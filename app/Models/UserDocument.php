<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserDocument extends Model
{
    use HasFactory;

    protected $table = 'user_documents';

    protected $fillable = [
        'user_id',
        'document_type',
        'document_name',
        'drive_file_id',
        'drive_file_url',
        'notes',
        'uploaded_by',
    ];

    protected $casts = [
        'user_id'     => 'integer',
        'uploaded_by' => 'integer',
    ];

    /**
     * Utente a cui appartiene il documento.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Utente che ha caricato il documento.
     */
    public function uploadedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}