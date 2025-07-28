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
        return view('documents.documents', [
            'user' => auth()->user(),
        ]);
    }
}
