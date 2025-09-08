<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CustomerController extends BaseController
{
    public function index()
    {
        // Mostra la vista con la lista dei clienti
        return view('customers.index');
    }

    public function addCustomer(Request $request, \App\Services\GoogleDriveService $driveService)
    {
        $validated = $request->validate([
            // campi customer
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'tax_code' => 'nullable|string|max:20',
            'vat_number' => 'nullable|string|max:20',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'state' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'customer_type' => 'required|in:individual,company',
            'notes' => 'nullable|string|max:255',

            // campi document
            'document_number' => 'required|string|max:255',
            'document_type' => 'required|string|max:100',
            'expiry_date' => 'required|date',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240'
        ]);

        //Creo il customer
        $customerData = collect($validated)->except([
            'document_number', 'document_type', 'expiry_date', 'document'
        ])->toArray();

        $customer = \App\Models\Customer::create($customerData);

        // Gestisco l’upload del documento
        $uploadedFile = $request->file('document');
        $filePath = $uploadedFile->getPathname();
        //Genero il nome del file da first_name - last_name - company name - document_type - document_number
        $fileName = "{$validated['first_name']}_{$validated['last_name']}_{$validated['company_name']}_{$validated['document_type']}_{$validated['document_number']}.{$uploadedFile->getClientOriginalExtension()}";

        $result = $driveService->uploadToAutonoleggio($filePath, $fileName, auth()->user(),"Documenti Clienti");

        // Salvo il documento collegato al customer
        $customer->documents()->create([
            'id_document_number' => $validated['document_number'],
            'document_type' => $validated['document_type'],
            'expiry_date' => $validated['expiry_date'],
            'drive_file_id' => $result['id'],     // salvi l’id
            'drive_file_url' => $result['url'],   // salvi anche l’url
            'uploaded_by' => auth()->user()->id
        ]);

        return response()->json($customer->load('documents'), 201);
    }

    public function addDocument(Request $request, \App\Services\GoogleDriveService $driveService)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'id_document_number' => 'required|string|max:255',
            'document_type' => 'required|string|max:100',
            'expiry_date' => 'required|date',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240'
        ]);

        // Trova il cliente
        $customer = \App\Models\Customer::findOrFail($validated['customer_id']);

        // Gestisco l’upload del documento
        $uploadedFile = $request->file('document');
        $filePath = $uploadedFile->getPathname();
        //Genero il nome del file da first_name - last_name - company name - document_type - document_number
        $fileName = "{$customer->first_name}_{$customer->last_name}_{$customer->company_name}_{$validated['document_type']}_{$validated['id_document_number']}.{$uploadedFile->getClientOriginalExtension()}";

        $result = $driveService->uploadToAutonoleggio($filePath, $fileName, auth()->user(),"Documenti Clienti");

        // Salvo il documento collegato al customer
        $document = $customer->documents()->create([
            'customer_id' => $customer->id,
            'id_document_number' => $validated['id_document_number'],
            'document_type' => $validated['document_type'],
            'expiry_date' => $validated['expiry_date'],
            'drive_file_id' => $result['id'],     // salvi l’id
            'drive_file_url' => $result['url'],   // salvi anche l’url
            'uploaded_by' => auth()->user()->id
        ]);

        return response()->json($document, 201);
    }

    public function getCustomerById(Request $request, $id)
    {
        // Logica per ottenere un cliente per ID
        $customer = \App\Models\Customer::find($id);
        if (!$customer) {
            return response()->json(['message' => 'Customer non trovato'], 404);
        }
        return response()->json($customer);
    }

    public function getDocumentsByCustomerId(Request $request, $id)
    {
        // Logica per ottenere i documenti di un cliente per ID
        $customer = \App\Models\Customer::with('documents')->find($id);
        if (!$customer) {
            //se non esiste il cliente ritorno array vuoto
            return response()->json([], 200);
        }
        return response()->json($customer->documents);
    }

    public function getDocumentById(Request $request, $documentId)
    {
        // Logica per ottenere un documento specifico di un cliente
        $document = \App\Models\PersonDocument::where('id', $documentId)->first();
        if (!$document) {
            return response()->json(['message' => 'Documento non trovato'], 404);
        }
        return response()->json($document);
    }

    public function delete(Request $request, $id)
    {
         // Trova il cliente con i documenti
        $customer = \App\Models\Customer::with('documents')->findOrFail($id);

        // Elimina i file dal Drive
        $driveService = app(\App\Services\GoogleDriveService::class);

        foreach ($customer->documents as $doc) {
            if (!empty($doc->drive_file_id)) {
                $driveService->deleteFile($doc->drive_file_id, auth()->user());
            }
        }

        // Elimina il cliente
        $customer->delete();

        return response()->noContent();
    }

    public function deleteDocument(Request $request, $customerId, $documentId)
    {
        // Trova il documento
        $document = \App\Models\PersonDocument::findOrFail($documentId);

        // Elimina il file dal Drive
        $driveService = app(\App\Services\GoogleDriveService::class);
        if (!empty($document->drive_file_id)) {
            $driveService->deleteFile($document->drive_file_id, auth()->user());
        }

        // Elimina il documento
        $document->delete();

        return response()->noContent();
    }

    public function getCustomers(Request $request, $filter)
    {
        // Prendi i parametri di paginazione dalla query string (default: pagina 1, 10 elementi per pagina)
        $page = (int)$request->query('page', 1);
        $pageSize = (int)$request->query('pageSize', 10);
    
        //Parametro di ricerca
        $search = trim($request->query('search', ''));

        // Crea la query base sul modello Customer
        $query = \App\Models\Customer::with(['documents:id,customer_id,id_document_number']);

        // Applica il filtro se non è "all"
        if ($filter && $filter !== "all") {
            $query->where('customer_type', $filter);
        }

        //Filtro di ricerca
        if ($search !== '') {
            $searchTerms = preg_split('/\s+/', $search); // divide la stringa in parole
            $query->where(function($q) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $q->where(function($qq) use ($term) {
                $searchLike = "%{$term}%";
                $qq->where('first_name', 'LIKE', $searchLike)
                   ->orWhere('last_name', 'LIKE', $searchLike)
                   ->orWhere('email', 'LIKE', $searchLike)
                   ->orWhere('phone', 'LIKE', $searchLike)
                   ->orWhere('address', 'LIKE', $searchLike)
                   ->orWhere('customer_type', 'LIKE', $searchLike)
                   ->orWhere('city', 'LIKE', $searchLike)
                   ->orWhere('postal_code', 'LIKE', $searchLike)
                   ->orWhere('country', 'LIKE', $searchLike)
                   ->orWhere('tax_code', 'LIKE', $searchLike)
                   ->orWhere('vat_number', 'LIKE', $searchLike)
                   ->orWhere('company_name', 'LIKE', $searchLike);
                    });
                }
            });
        }

        // Conta il totale dei clienti corrispondenti al filtro (serve per la paginazione)
        $total = $query->count();

        // Prendi solo i clienti della pagina richiesta, ordinati per id
        $customers = $query->offset(($page - 1) * $pageSize)
                          ->limit($pageSize)
                          ->orderBy('id')
                          ->get()
                          ->map(function($customer) {
                                // Aggiungo un campo "document_number" direttamente sul customer
                                $customer->document_number = $customer->documents->first()->id_document_number ?? null;
                                // Nascondo la relazione per non avere tutto l'array
                                unset($customer->documents);
                                return $customer;
                          });

        // Restituisci i dati in formato JSON (clienti della pagina e il totale)
        return response()->json([
            'customers' => $customers,
            'total' => $total
        ]);
    }

    public function updateDocument(Request $request, $id, \App\Services\GoogleDriveService $driveService)
{
    $document = \App\Models\PersonDocument::findOrFail($id);

    // Validazione
    $validated = $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'id_document_number' => 'required|string|max:255',
        'document_type' => 'required|string|max:100',
        'expiry_date' => 'nullable|date',
        'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240'
    ]);

    $customer = \App\Models\Customer::findOrFail($validated['customer_id']);

    // Costruisci il nuovo nome file
    $newFileName = "{$customer->first_name}_{$customer->last_name}_{$customer->company_name}_{$validated['document_type']}_{$validated['id_document_number']}";
    
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
                "Documenti Clienti"
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
    $document->id_document_number = $validated['id_document_number'];
    $document->document_type = $validated['document_type'];
    $document->expiry_date = $validated['expiry_date'];
    $document->customer_id = $validated['customer_id'];
    $document->uploaded_by = auth()->user()->id;

    $document->save();

    // Risposta JSON per riempire il form in JS
    return response()->json([
        'id' => $document->id,
        'document_type' => $document->document_type,
        'id_document_number' => $document->id_document_number,
        'expiry_date' => $document->expiry_date,
        'customer_id' => $document->customer_id,
        'drive_file_url' => $document->drive_file_url,
    ]);
}

public function updateCustomer(Request $request, $id)
{
    $customer = \App\Models\Customer::findOrFail($id);

    // Validazione
    $validated = $request->validate([
        'first_name' => 'nullable|string|max:255',
        'last_name' => 'nullable|string|max:255',
        'company_name' => 'nullable|string|max:255',
        'tax_code' => 'nullable|string|max:20',
        'vat_number' => 'nullable|string|max:20',
        'email' => 'required|string|email|max:255',
        'phone' => 'required|string|max:20',
        'address' => 'required|string|max:255',
        'state' => 'required|string|max:100',
        'city' => 'required|string|max:100',
        'postal_code' => 'required|string|max:20',
        'customer_type' => 'required|in:individual,company',
        'notes' => 'nullable|string|max:255',
    ]);

    // Aggiorno i campi del cliente
    $customer->update($validated);

    // Risposta JSON per riempire il form in JS
    return response()->json($customer);
}
}