<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Vehicle;
use App\Models\RentalContract;
use Carbon\Carbon;

class CalendarDataController extends BaseController
{
    /**
     * Restituisce JSON con tutti i veicoli attivi e i contratti (bookings) per ciascun veicolo.
     * Possiamo filtrare per intervallo di date (opzionale) via query string: ?from=YYYY-MM-DD&to=YYYY-MM-DD
     */
    public function index(Request $request)
    {
        // 1) Carico tutti i veicoli (eventualmente filtrare per status se volete mostrare solo "available")
        $vehicles = Vehicle::select('id', 'brand', 'model')
            // ->where('status', 'available')
            ->get()
            ->map(function ($v) {
                // Componiamo un nome leggibile, ad esempio "Fiat Panda (123-ABC)"
                return [
                    'id'   => $v->id,
                    'name' => $v->brand . ' ' . $v->model,
                ];
            });

        // 2) Determiniamo se è stato passato un intervallo di date
        //    (utile per inviare al front-end solo le prenotazioni che cadono nel periodo visibile)
        $from = $request->query('from'); // es. "2025-01-01"
        $to   = $request->query('to');   // es. "2025-12-31"

        // Impostiamo un minimo/massimo per evitare query troppo vaste (es. ±1 anno rispetto a oggi)
        $today = Carbon::today();

        if (!$from) {
            $from = $today->copy()->subMonths(12)->toDateString();
        }
        if (!$to) {
            $to = $today->copy()->addMonths(12)->toDateString();
        }

        // 3) Carichiamo i contratti attivi (stato in ["pending", "active", "completed", ...])
        //    che abbiano almeno un giorno di sovrapposizione con [from, to]
        $rawBookings = RentalContract::select('vehicle_id', 'id', 'start_date', 'end_date')
            ->whereIn('status', ['pending', 'active', 'completed'])
            ->where(function ($q) use ($from, $to) {
                // Sovrapposizione intervallo: start_date <= to AND end_date >= from
                $q->where('start_date', '<=', $to)
                  ->where('end_date', '>=', $from);
            })
            ->get();

        // 4) Raggruppiamo i contratti per vehicle_id
        $bookingsPerVehicle = [];
        foreach ($rawBookings as $b) {
            $vid = $b->vehicle_id;
            if (!isset($bookingsPerVehicle[$vid])) {
                $bookingsPerVehicle[$vid] = [];
            }
            $bookingsPerVehicle[$vid][] = [
                'id'         => $b->id,
                'start_date' => $b->start_date->toDateString(),
                'end_date'   => $b->end_date->toDateString(),
            ];
        }

        // 5) Restituiamo JSON
        return response()->json([
            'vehicles' => $vehicles,
            'bookings' => $bookingsPerVehicle
        ]);
    }
}
