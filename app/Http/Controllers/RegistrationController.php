<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RegistrationController extends BaseController
{
    public function showRegistrationForm()
    {
        // Mostra il modulo di registrazione
        return view('registration');
    }

    public function doRegister(Request $request)
    {
        // Validazione dei dati di input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Creazione dell'utente
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        // Imposta il campo 'last_login_at' a null
        $user->last_login_at = null;
        $user->save(); // Salva l'utente nel database

        // Autenticazione dell'utente appena registrato
        auth()->login($user);

        // Reindirizza l'utente alla dashboard
        return redirect()->intended('dashboard');
    }
}