<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
                          ->orderBy('id')
                          ->get();

        // Restituisci i dati in formato JSON (clienti della pagina e il totale)
        return response()->json([
            'reservations' => $reservations,
            'total' => $total
        ]);
    }
}
