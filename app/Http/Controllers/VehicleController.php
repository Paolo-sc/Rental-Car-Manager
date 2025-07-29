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

}
