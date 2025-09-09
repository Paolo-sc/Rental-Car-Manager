<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DriverController extends BaseController
{
    public function index()
    {
        // Mostra la vista con la lista dei conducenti
        return view('drivers.index');
    }

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
}
