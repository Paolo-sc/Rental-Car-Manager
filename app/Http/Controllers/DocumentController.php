<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DocumentController extends BaseController
{
    public function showDocuments()
    {
        // Mostra i documenti dell'utente autenticato
        return view('documents', [
            'user' => auth()->user(),
        ]);
    }

    public function uploadDocument(Request $request)
    {
        // Logica per caricare un documento
    }
}
