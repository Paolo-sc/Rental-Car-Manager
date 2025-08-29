<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VehicleController extends BaseController
{
    public function index()
    {        
        // Mostra la vista con la lista dei veicoli
        return view('vehicles.index');
    }

    public function delete(Request $request, $id)
    {
        // Trova il veicolo da eliminare
        $vehicle = \App\Models\Vehicle::findOrFail($id);
        // Elimina il veicolo
        $vehicle->delete();
        return response()->noContent();
    }

    public function addVehicle(Request $request)
    {
        // Valida i dati del veicolo
        $validated = $request->validate([
            'license_plate' => 'required|string|max:10',
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'year' => 'required|integer|min:1886|max:' . date('Y'),
            'color' => 'required|string|max:30',
            'fuel_type' => 'required|string|max:20',
            'transmission' => 'required|string|max:20',
            'seats' => 'required|integer|min:1',
            'vin' => 'required|string|max:17',
            'engine_size' => 'required|numeric|min:0',
            'mileage' => 'required|numeric|min:0',
            'status' => 'required|string|in:Disponibile,Archiviato,Manutenzione',
            'notes' => 'nullable|string|max:255',
        ]);

        // Crea un nuovo veicolo
        $vehicle = \App\Models\Vehicle::create($validated);

        // Restituisci una risposta in caso di successo
        return response()->json([
            'message' => 'Veicolo aggiunto con successo.',
            'vehicle' => $vehicle
        ], 201);
    }

    public function getVehicleById(Request $request, $id)
    {
        // Trova il veicolo per ID
        $vehicle = \App\Models\Vehicle::findOrFail($id);
        return response()->json($vehicle);
    }

    public function updateVehicle(Request $request, $id)
    {
        // Trova il veicolo da aggiornare
        $vehicle = \App\Models\Vehicle::findOrFail($id);

        // Valida i dati del veicolo
        $validated = $request->validate([
            'license_plate' => 'required|string|max:10',
            'brand' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'year' => 'required|integer|min:1886|max:' . date('Y'),
            'color' => 'required|string|max:30',
            'fuel_type' => 'required|string|max:20',
            'transmission' => 'required|string|max:20',
            'seats' => 'required|integer|min:1',
            'vin' => 'required|string|max:17',
            'engine_size' => 'required|numeric|min:0',
            'mileage' => 'required|numeric|min:0',
            'status' => 'required|string|in:Disponibile,Archiviato,Manutenzione',
            'notes' => 'nullable|string|max:255',
        ]);

        // Aggiorna il veicolo
        $vehicle->update($validated);

        // Restituisci una risposta in caso di successo
        return response()->json([
            'message' => 'Veicolo aggiornato con successo.',
            'vehicle' => $vehicle
        ]);
    }

    public function getVehicles(Request $request, $status)
    {
        // Prendi i parametri di paginazione dalla query string (default: pagina 1, 10 elementi per pagina)
        $page = (int)$request->query('page', 1);
        $pageSize = (int)$request->query('pageSize', 10);
    
        //Parametro di ricerca
        $search = trim($request->query('search', ''));

        // Crea la query base sul modello Vehicle
        $query = \App\Models\Vehicle::query();

        // Applica il filtro: se status == 'archived' mostra solo archiviati, altrimenti mostra solo non archiviati
        if ($status === 'archived') {
            $query->where('status', 'Archiviato');
        } else {
            $query->where('status', '!=', 'Archiviato');
        }

        //Filtro di ricerca
        if ($search !== '') {
            $searchTerms = preg_split('/\s+/', $search); // divide la stringa in parole
            $query->where(function($q) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $q->where(function($qq) use ($term) {
                $searchLike = "%{$term}%";
                $qq->where('license_plate', 'LIKE', $searchLike)
                   ->orWhere('vin', 'LIKE', $searchLike)
                   ->orWhere('brand', 'LIKE', $searchLike)
                   ->orWhere('model', 'LIKE', $searchLike)
                   ->orWhere('transmission', 'LIKE', $searchLike)
                   ->orWhere('fuel_type', 'LIKE', $searchLike)
                   ->orWhere('status', 'LIKE', $searchLike);

                    });
                }
            });
        }

        // Conta il totale dei veicoli corrispondenti al filtro (serve per la paginazione)
        $total = $query->count();

        // Prendi solo i veicoli della pagina richiesta, ordinati per id
        $vehicles = $query->offset(($page - 1) * $pageSize)
                          ->limit($pageSize)
                          ->orderBy('id')
                          ->get();

        // Restituisci i dati in formato JSON (veicoli della pagina e il totale)
        return response()->json([
            'vehicles' => $vehicles,
            'total' => $total
        ]);
    }
}
