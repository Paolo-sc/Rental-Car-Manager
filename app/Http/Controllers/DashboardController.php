<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DashboardController extends BaseController
{
    public function showDashboard()
    {
        // Mostra la dashboard dell'utente autenticato
        return view('dashboard', [
            'user' => auth()->user(),
        ]);
    }
}
