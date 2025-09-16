<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DriverController extends BaseController
{
public function search(Request $request)
{
    $q = trim($request->query('q', ''));
    if (!$q) return response()->json([]);

    $results = \App\Models\Driver::where(function($qq) use ($q) {
        $qq->where('first_name', 'like', "%$q%")
           ->orWhere('last_name', 'like', "%$q%")
           ->orWhere('email', 'like', "%$q%")
           ->orWhere('phone', 'like', "%$q%");
    })
    ->orderBy('first_name')
    ->limit(10)
    ->get([
        'id', 'first_name', 'last_name', 'email', 'phone'
    ]);

    // Prepara un campo di visualizzazione "smart"
    $results->each(function($c) {
        $c->display = trim(($c->first_name ?? '') . ' ' . ($c->last_name ?? ''));
        if ($c->email) $c->display .= " ({$c->email})";
    });

    return response()->json($results);
}

public function getDrivers(Request $request, $reservation_id)
{
    $drivers = \App\Models\RentalContractDriver::with('driver')
        ->where('rental_contract_id', $reservation_id)
        ->get()
        ->pluck('driver');

    return response()->json($drivers);
}

public function addDriverToReservation(Request $request, $reservation_id)
{
    \Log::info('Request payload:', $request->all());
    //Fai questo solo se nel request c'è driver_id (conducente esistente) altrimenti crea un nuovo conducente e lo associa alla prenotazione
    if ($request->has('driver_id')) {
    $validatedData = $request->validate([
        'driver_id' => 'required|exists:drivers,id',
    ]);

    // Verifica se il driver è già associato alla prenotazione
    $existing = \App\Models\RentalContractDriver::where('rental_contract_id', $reservation_id)
        ->where('driver_id', $validatedData['driver_id'])
        ->first();

    if ($existing) {
        return response()->json(['message' => 'Il conducente è già associato a questa prenotazione.'], 400);
    }

    $rentalContractDriver = \App\Models\RentalContractDriver::create([
        'rental_contract_id' => $reservation_id,
        'driver_id' => $validatedData['driver_id'],
    ]);

    return response()->json($rentalContractDriver, 201);
 } else if ($request->has('new_driver')) {
    \Log::info('Creazione nuovo conducente con dati:', $request->input('new_driver'));

        // Crea il nuovo conducente
        $driverData = $request->input('new_driver');
        $validatedDriverData = \Validator::make($driverData, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'driving_license_number' => 'nullable|string|max:100',
            'driving_license_issue_place' => 'nullable|string|max:255',
            'driving_license_issue_date' => 'nullable|date_format:d/m/Y',
            'driving_license_expires_at' => 'nullable|date_format:d/m/Y',
            'tax_code' => 'nullable|string|max:50',
            'birth_date' => 'nullable|date_format:d/m/Y',
            'birth_place' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',  
        ]);
        $driverData['created_by'] = auth()->id();
        \Log::info('Dati conducente da creare:', $driverData);

        //Se il conducente gia esiste ritorna un json che dice che il conducente esiste gia
        $existingDriver = \App\Models\Driver::where('first_name', $driverData['first_name'])
            ->where('last_name', $driverData['last_name'])
            ->where('email', $driverData['email'])
            ->first();
        if ($existingDriver) {
            return response()->json(['message' => 'Il conducente esiste già.'], 400);
        }

        $driver = \App\Models\Driver::create($driverData);
        \Log::info('Nuovo conducente creato:', $driver->toArray());

        // Associa il nuovo conducente alla prenotazione
        $rentalContractDriver = \App\Models\RentalContractDriver::create([
            'rental_contract_id' => $reservation_id,
            'driver_id' => $driver->id,
        ]);

        return response()->json($rentalContractDriver, 201);
    } else {
        throw ValidationException::withMessages(['driver_id' => 'Devi fornire un driver_id o i dati per creare un nuovo conducente.']);
    }
}
}