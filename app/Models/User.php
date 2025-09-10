<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * Gli attributi che si possono assegnare in massa.
     */
    protected $fillable = [
        'email',
        'email_verified_at',
        'password',
        'first_name',
        'last_name',
        'phone',
        'status',
        'invitation_token',
        'invitation_expires_at',
        'last_login_at',
        'created_by',
        'remember_token',
        'google_drive_token',
        'google_drive_name'
    ];

    /**
     * Gli attributi da nascondere in JSON/serializzazione.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * I cast automatici per i campi data/boolean.
     */
    protected $casts = [
        'email_verified_at'     => 'datetime',
        'last_login_at'         => 'datetime',
        'created_by'            => 'integer',
    ];

    /**
     * Accessor: fullName calcola “first_name + ' ' + last_name”.
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => trim($this->first_name . ' ' . $this->last_name),
        );
    }

    /**
     * Relazione 1:N: tutti gli utenti creati da questo utente.
     */
    public function createdUsers(): HasMany
    {
        return $this->hasMany(User::class, 'created_by');
    }

    /**
     * Tutti i contratti di noleggio creati da questo utente (field created_by in rental_contracts).
     */
    public function rentalContracts(): HasMany
    {
        return $this->hasMany(RentalContract::class, 'created_by');
    }

    /**
     * Documenti di persone caricati da questo utente (tabella person_documents).
     */
    public function personDocuments(): HasMany
    {
        return $this->hasMany(PersonDocument::class, 'uploaded_by');
    }

    /**
     * Documenti di veicoli caricati da questo utente (tabella vehicle_documents).
     */
    public function vehicleDocuments(): HasMany
    {
        return $this->hasMany(VehicleDocument::class, 'uploaded_by');
    }

    /**
     * Ispezioni veicoli effettuate da questo utente (tabella vehicle_inspections).
     */
    public function vehicleInspections(): HasMany
    {
        return $this->hasMany(VehicleInspection::class, 'inspected_by');
    }

    /**
     * Spese aggiuntive inserite da questo utente (tabella additional_charges).
     */
    public function additionalCharges(): HasMany
    {
        return $this->hasMany(AdditionalCharge::class, 'added_by');
    }

    public function isGoogleDriveConnected(): bool
{
    if (!$this->google_drive_token) return false;

    $token = json_decode($this->google_drive_token, true);

    if (empty($token)) return false;

    // Controlla se c'è expiry_date e se è scaduto
    if (!empty($token['expires_in']) && !empty($token['created'])) {
        $expireTime = $token['created'] + $token['expires_in'];
        if (time() >= $expireTime) {
            return false; // token scaduto
        }
    }

    return true;
}
}
