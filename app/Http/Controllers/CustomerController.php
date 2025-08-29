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

    public function addCustomer(Request $request)
    {
        // Logica per aggiungere un nuovo cliente
    }

    public function getCustomers(Request $request)
    {
        // Prendi i parametri di paginazione dalla query string (default: pagina 1, 10 elementi per pagina)
        $page = (int)$request->query('page', 1);
        $pageSize = (int)$request->query('pageSize', 10);
    
        //Parametro di ricerca
        $search = trim($request->query('search', ''));

        // Crea la query base sul modello Customer
        $query = \App\Models\Customer::query();

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
                   ->orWhere('status', 'LIKE', $searchLike)
                   ->orWhere('customer_type', 'LIKE', $searchLike)
                   ->orWhere('city', 'LIKE', $searchLike)
                   ->orWhere('postal_code', 'LIKE', $searchLike)
                   ->orWhere('country', 'LIKE', $searchLike)
                   ->orWhere('tax_code', 'LIKE', $searchLike)
                   ->orWhere('vat_number', 'LIKE', $searchLike);
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
                          ->get();

        // Restituisci i dati in formato JSON (clienti della pagina e il totale)
        return response()->json([
            'customers' => $customers,
            'total' => $total
        ]);
    }
}
