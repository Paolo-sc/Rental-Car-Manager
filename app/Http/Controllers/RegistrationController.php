<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RegistrationController extends BaseController
{
    public function showRegistrationFormWithToken($token)
    {
        // Verifica se il token esiste e non è stato utilizzato
        $invitation = \App\Models\Invitation::where('token', $token)->where('used', false)->first();

        if (!$invitation || $invitation->expires_at < now()) {
            return view('error', [
                'title' => 'Invito non valido o scaduto',
                'message' => 'Il link di invito non è più valido oppure è già stato usato.'
            ]);
        }

        // Mostra il modulo di registrazione con i dati dell'invito
        return view('register', ['invitation' => $invitation]);
    }

    public function doRegisterWithToken(Request $request)
    {
        // Validazione dei dati di input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'token' => ['required', 'string'],
        ]);

        // Recupera l'invito utilizzando il token
        $invitation = \App\Models\Invitation::where('token', $request->token)->where('used', false)->first();

        if (!$invitation || $invitation->expires_at < now()) {
            throw ValidationException::withMessages(['token' => 'Invito non valido o scaduto.']);
        }

        // Creazione dell'utente
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Imposta il campo 'last_login_at' a null
        $user->last_login_at = null;
        $user->save(); // Salva l'utente nel database

        // Segna l'invito come utilizzato
        $invitation->used = true;
        $invitation->save();

        // Autenticazione dell'utente appena registrato
        auth()->login($user);

        // Reindirizza l'utente alla dashboard
        return redirect()->intended('dashboard');
    }
}