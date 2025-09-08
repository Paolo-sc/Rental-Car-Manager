<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

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

    public function deleteDocument(Request $request, $vehicleId, $documentId)
    {
        // Trova il documento
        $document = \App\Models\VehicleDocument::findOrFail($documentId);

        // Elimina il file dal Drive
        $driveService = app(\App\Services\GoogleDriveService::class);
        if (!empty($document->drive_file_id)) {
            $driveService->deleteFile($document->drive_file_id, auth()->user());
        }

        // Elimina il documento
        $document->delete();

        return response()->noContent();
    }

    public function getVehicleById(Request $request, $id)
    {
        // Trova il veicolo per ID
        $vehicle = \App\Models\Vehicle::findOrFail($id);
        if(!$vehicle) {
            return response()->json(['message' => 'Veicolo non trovato'], 404);
        }
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

    public function getDocumentsByVehicleId(Request $request, $id)
    {
        // Logica per ottenere i documenti di un cliente per ID
        $vehicle = \App\Models\Vehicle::with('documents')->find($id);
        if (!$vehicle) {
            //se non esiste il cliente ritorno array vuoto
            return response()->json([], 200);
        }
        return response()->json($vehicle->documents);
    }

    public function addDocument(Request $request, \App\Services\GoogleDriveService $driveService)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'document_name' => 'required|string|max:255',
            'document_type' => 'required|string|max:100',
            'notes' => 'nullable|string|max:255',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240'
        ]);

        // Trova il veicolo
        $vehicle = \App\Models\Vehicle::findOrFail($validated['vehicle_id']);

        // Gestisco l’upload del documento
        $uploadedFile = $request->file('document');
        $filePath = $uploadedFile->getPathname();
        //Sanifico il nome del file
        $safeBrand = Str::slug($vehicle->brand, '_');
        $safeModel = Str::slug($vehicle->model, '_');
        $safeDocType = Str::slug($validated['document_type'], '_');
        //Genero il nome del file da first_name - last_name - company name - document_type - document_number
        $fileName = "{$vehicle->license_plate}_{$safeBrand}_{$safeModel}_{$safeDocType}_{$validated['document_name']}.{$uploadedFile->getClientOriginalExtension()}";

        $result = $driveService->uploadToAutonoleggio($filePath, $fileName, auth()->user(),"Documenti Veicoli");

        // Salvo il documento collegato al vehicle
        $document = $vehicle->documents()->create([
            'vehicle_id' => $vehicle->id,
            'document_name' => $validated['document_name'],
            'document_type' => $validated['document_type'],
            'drive_file_id' => $result['id'],     // salvi l’id
            'drive_file_url' => $result['url'],   // salvi anche l’url
            'notes' => $validated['notes'] ?? null,
            'uploaded_by' => auth()->user()->id
        ]);

        return response()->json($document, 201);
    }

     public function getDocumentById(Request $request, $documentId)
    {
        // Logica per ottenere un documento specifico di un cliente
        $document = \App\Models\VehicleDocument::where('id', $documentId)->first();
        if (!$document) {
            return response()->json(['message' => 'Documento non trovato'], 404);
        }
        return response()->json($document);
    }

    public function updateDocument(Request $request, $id, \App\Services\GoogleDriveService $driveService)
{
    $document = \App\Models\VehicleDocument::findOrFail($id);

    // Validazione
    $validated = $request->validate([
        'vehicle_id' => 'required|exists:vehicles,id',
        'document_name' => 'required|string|max:255',
        'document_type' => 'required|string|max:100',
        'notes' => 'nullable|string|max:255',
        'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240'
    ]);

    $vehicle = \App\Models\Vehicle::findOrFail($validated['vehicle_id']);

    //Sanifico il nome del file
    $safeBrand = Str::slug($vehicle->brand, '_');
    $safeModel = Str::slug($vehicle->model, '_');
    $safeDocType = Str::slug($validated['document_type'], '_');

    // Costruisci il nuovo nome file
    $newFileName = "{$safeBrand}_{$safeModel}_{$vehicle->license_plate}_{$safeDocType}_{$validated['document_name']}";

    if ($request->hasFile('document')) {
        $uploadedFile = $request->file('document');
        $filePath = $uploadedFile->getPathname();
        $newFileName .= ".{$uploadedFile->getClientOriginalExtension()}";

        if ($document->drive_file_id) {
            // Se esiste già un file su Drive, lo sostituisci
            $result = $driveService->replaceFile(
                $document->drive_file_id,
                $filePath,
                $newFileName,
                auth()->user()
            );
        } else {
            // Altrimenti carica un nuovo file
            $newFileName .= ".{$uploadedFile->getClientOriginalExtension()}";
            $result = $driveService->uploadToAutonoleggio(
                $filePath,
                $newFileName,
                auth()->user(),
                "Documenti Veicoli"
            );
        }

        $document->drive_file_id = $result['id'];
        $document->drive_file_url = $result['url'];
    } else if ($document->drive_file_id) {
        // L’utente ha modificato solo il nome o altri campi → rinomina su Drive
        $extension = pathinfo($document->drive_file_url, PATHINFO_EXTENSION);
        $result = $driveService->renameFile(
            $document->drive_file_id,
            $newFileName . ($extension ? ".{$extension}" : ""),
            auth()->user()
        );
        $document->drive_file_url = $result['url'];
    }

    // Aggiorno i campi testuali
    $document->document_name = $validated['document_name'];
    $document->document_type = $validated['document_type'];
    $document->notes = $validated['notes'] ?? null;
    $document->vehicle_id = $validated['vehicle_id'];
    $document->uploaded_by = auth()->user()->id;

    $document->save();

    // Risposta JSON per riempire il form in JS
    return response()->json([
        'id' => $document->id,
        'document_type' => $document->document_type,
        'document_name' => $document->document_name,
        'notes' => $document->notes,
        'vehicle_id' => $document->vehicle_id,
        'drive_file_url' => $document->drive_file_url,
    ]);
}
}
