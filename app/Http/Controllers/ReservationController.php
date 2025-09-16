<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Storage;
use App\Models\RentalContract;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Services\GoogleDriveService;

class ReservationController extends BaseController
{
    public function showReservation(){
        //Mostro la pagina delle prenotazioni
        return view('reservations.index');
    }

    public function getReservation(Request $request)
    {
        // Prendi i parametri di paginazione dalla query string (default: pagina 1, 10 elementi per pagina)
        $page = (int)$request->query('page', 1);
        $pageSize = (int)$request->query('pageSize', 10);
    
        //Parametro di ricerca
        $search = trim($request->query('search', ''));

        // Crea la query base sul modello RentalContract
        $query = \App\Models\RentalContract::query();

        //Filtro di ricerca
        if ($search !== '') {
            $searchTerms = preg_split('/\s+/', $search); // divide la stringa in parole
            $query->where(function($q) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $q->where(function($qq) use ($term) {
                $searchLike = "%{$term}%";
                $qq->where('contract_number', 'LIKE', $searchLike)
                   ->orWhere('booking_code', 'LIKE', $searchLike);
                    });
                }
            });
        }

        // Conta il totale dei clienti corrispondenti al filtro (serve per la paginazione)
        $total = $query->count();

        // Prendi solo le prenotazioni della pagina richiesta, ordinati per id
        $reservations = $query->with(['vehicle','customer'])
                          ->offset(($page - 1) * $pageSize)
                          ->limit($pageSize)
                          ->orderBy('start_date')
                          ->get();

        // Restituisci i dati in formato JSON (clienti della pagina e il totale)
        return response()->json([
            'reservations' => $reservations,
            'total' => $total
        ]);
    }

    public function delete(Request $request, $id){
        //Trova la prenotazione da eliminare
        $reservation = \App\Models\RentalContract::findOrFail($id);

        //Elimina il cliente
        $reservation->delete();

        return response()->noContent();
    }

    public function addReservation(Request $request, \App\Services\GoogleDriveService $driveService)
{
    // Validazione dei dati
    $validatedData = $request->validate([
        'booking_code' => 'required|string|max:255|unique:rental_contracts,booking_code',
        'vehicle_id' => 'required|exists:vehicles,id',
        'customer_id' => 'required|exists:customers,id',
        'main_driver_id' => 'nullable|exists:drivers,id',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'pickup_time' => 'nullable|string|max:255',
        'return_time' => 'nullable|string|max:255',
        'pickup_location' => 'nullable|string|max:255',
        'return_location' => 'nullable|string|max:255',
        'daily_rate' => 'nullable|numeric|min:0',
        'deductible_damage' => 'nullable|numeric|min:0',
        'deductible_rca' => 'nullable|numeric|min:0',
        'deposit_amount' => 'nullable|numeric|min:0',
        'deposit_payment_method' => 'nullable|string|max:255',
        'discount_amount' => 'nullable|numeric|min:0',
        'franchise_theft_fire' => 'nullable|numeric|min:0',
        'km_included_type' => 'nullable|in:limited,unlimited',
        'km_included_value' => 'nullable|numeric|min:0',
        'subtotal' => 'nullable|numeric|min:0',
        'tax_rate' => 'nullable|numeric|min:0',
        'total_amount' => 'nullable|numeric|min:0',
        'total_days' => 'nullable|numeric|min:0',
        'total_paid' => 'nullable|numeric|min:0',
        'payment_date' => 'nullable|date',
        'payment_method' => 'nullable|string|max:255',
        'payment_received' => 'nullable|boolean',
        'customer_signature_obtained' => 'nullable|boolean',
        'customer_signature_required' => 'nullable|boolean',
        'signature_date' => 'nullable|date',
        'special_conditions' => 'nullable|string|max:1000',
        'notes' => 'nullable|string|max:1000',
        'reservation_status' => 'nullable|string|max:255',
        'status' => 'nullable|string|max:255',
        'tax_amount' => 'nullable|numeric|min:0',
    ]);

    //Se esiste gia una prenotazione per quella macchina in quel periodo ritorna errore
    $existingReservation = \App\Models\RentalContract::where('vehicle_id', $validatedData['vehicle_id'])
        ->where(function($query) use ($validatedData) {
            $query->whereBetween('start_date', [$validatedData['start_date'], $validatedData['end_date']])
                  ->orWhereBetween('end_date', [$validatedData['start_date'], $validatedData['end_date']])
                  ->orWhere(function($q) use ($validatedData) {
                      $q->where('start_date', '<=', $validatedData['start_date'])
                        ->where('end_date', '>=', $validatedData['end_date']);
                  });
        })
        ->first();
    if ($existingReservation) {
        //ritorna un json con messagio di errore
        return response()->json([
            'error' => 'Esiste già una prenotazione per questa macchina in questo periodo.'
        ], 422);
    }

    //  Creazione prenotazione e aggiunta il main driver id al model RentalContractDriver
    $reservation = \App\Models\RentalContract::create(
        array_merge($validatedData, ['created_by' => auth()->id()])
    );
    if (isset($validatedData['main_driver_id'])) {
        \App\Models\RentalContractDriver::create([
            'rental_contract_id' => $reservation->id,
            'driver_id' => $validatedData['main_driver_id'],
        ]);
    }

    //  Carica relazioni per il PDF
    $reservation->load(['vehicle', 'customer', 'mainDriver']);

    //  Generazione PDF in memoria
    $pdf = \PDF::loadView('contract', ['reservation' => $reservation]);
    $pdfContent = $pdf->output();

    //  Upload PDF su Google Drive nella cartella Autonoleggio/Contratti
    $file = $driveService->uploadPdfFromMemory(
        $pdfContent,
        'contratto_'.$reservation->booking_code.'_'.$reservation->customer->first_name.'_'.$reservation->customer->last_name.'.pdf',
        auth()->user(),
        'Contratti'
    );

    //  Aggiornamento prenotazione con ID e URL del contratto
    $reservation->update([
        'contract_pdf_drive_file_id' => $file['id'],
        'contract_pdf_drive_file_url' => $file['url'],
    ]);

    // Restituzione JSON con prenotazione e link al contratto
    return response()->json([
        'reservation' => $reservation,
        'contract_link' => $reservation->contract_pdf_drive_file_url
    ], 201);
}

public function signReservation(Request $request, $id, \App\Services\GoogleDriveService $driveService)
{
    $reservation = \App\Models\RentalContract::with(['vehicle', 'customer', 'mainDriver'])->findOrFail($id);

    $validated = $request->validate([
        'signature' => 'required|string',
    ]);

    // Genera il nuovo PDF con la firma
    $pdf = \PDF::loadView('contract', [
        'reservation' => $reservation,
        'signature_base64' => $validated['signature'],
    ]);
    $pdfContent = $pdf->output();

    $fileName = 'contratto_'.$reservation->booking_code.'_'.$reservation->customer->first_name.'_'.$reservation->customer->last_name.'.pdf';

    // Salva PDF temporaneo
    $tempPath = tempnam(sys_get_temp_dir(), 'pdf');
    file_put_contents($tempPath, $pdfContent);

    try {
        if ($reservation->contract_pdf_drive_file_id) {
            // SOVRASCRIVE il file esistente su Drive!
            $file = $driveService->replaceFile(
                $reservation->contract_pdf_drive_file_id,
                $tempPath,
                $fileName,
                auth()->user()
            );
        } else {
            // Primo upload
            $file = $driveService->uploadToAutonoleggio(
                $tempPath,
                $fileName,
                auth()->user(),
                'Contratti'
            );
        }

        $reservation->update([
            'contract_pdf_drive_file_id' => $file['id'],
            'contract_pdf_drive_file_url' => $file['url'],
            'customer_signature_obtained' => true,
            'signature_date' => now(),
        ]);
    } finally {
        if (file_exists($tempPath)) unlink($tempPath);
    }

    return response()->json([
        'success' => true,
        'contract_link' => $reservation->contract_pdf_drive_file_url,
    ]);
}

public function getReservationById($id)
    {
        // Trova la prenotazione per ID con relazioni
        $reservation = \App\Models\RentalContract::with(['vehicle', 'customer', 'mainDriver'])->find($id);

        if (!$reservation) {
            return response()->json(['error' => 'Prenotazione non trovata'], 404);
        }


        return response()->json($reservation);
    }

public function updateReservation(Request $request, $id, \App\Services\GoogleDriveService $driveService)
{
    // Trova la prenotazione esistente con relazioni
    $reservation = \App\Models\RentalContract::with(['vehicle', 'customer', 'mainDriver'])->findOrFail($id);


    // Aggiorna la prenotazione + reset firma
    $reservation->update(array_merge($request->all(), [
        'customer_signature_obtained' => false,
        'signature_date' => null,
    ]));

    // Ricarico relazioni aggiornate
    $reservation->load(['vehicle', 'customer', 'mainDriver']);

    // Genera il nuovo PDF
    $pdf = \PDF::loadView('contract', ['reservation' => $reservation]);
    $pdfContent = $pdf->output();

    $fileName = 'contratto_'.$reservation->booking_code.'_'.$reservation->customer->first_name.'_'.$reservation->customer->last_name.'.pdf';

    // Salva PDF temporaneo
    $tempPath = tempnam(sys_get_temp_dir(), 'pdf');
    file_put_contents($tempPath, $pdfContent);

    try {
        if ($reservation->contract_pdf_drive_file_id) {
            // Sostituisco il file esistente
            $file = $driveService->replaceFile(
                $reservation->contract_pdf_drive_file_id,
                $tempPath,
                $fileName,
                auth()->user()
            );
        } else {
            // Primo upload
            $file = $driveService->uploadToAutonoleggio(
                $tempPath,
                $fileName,
                auth()->user(),
                'Contratti'
            );
        }

        // Aggiorna la prenotazione con il nuovo link/ID
        $reservation->update([
            'contract_pdf_drive_file_id' => $file['id'],
            'contract_pdf_drive_file_url' => $file['url'],
        ]);
    } finally {
        if (file_exists($tempPath)) unlink($tempPath);
    }

    return response()->json([
        'success' => true,
        'contract_link' => $reservation->contract_pdf_drive_file_url,
    ]);
}

}
