<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VehicleController extends BaseController
{
    public function index()
    {
        // Recupera tutti i veicoli dal database
        $vehicles = \App\Models\Vehicle::all();
        
        // Mostra la vista con la lista dei veicoli
        return view('vehicles.index', compact('vehicles'));
    }

    public function destroy(Request $request, $id)
    {
    // Trova il veicolo da eliminare
    $vehicle = \App\Models\Vehicle::findOrFail($id);
    // Elimina il veicolo
    $vehicle->delete();

    return redirect()->route('vehicles.index')->with('success', 'Veicolo eliminato con successo!');
    }

    public function showArchivedVehicles()
    {
        // Recupera i veicoli archiviati dal database
        $archivedVehicles = \App\Models\Vehicle::where('archived', 1)->get();
        // Verifica se ci sono veicoli archiviati
        return response()->json($archivedVehicles, 200);
    }
}
