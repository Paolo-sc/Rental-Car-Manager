<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RegisterController extends BaseController
{
    public function showRegisterFormWithToken($token)
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
        return view('auth.register', compact('invitation'));
    }

    public function doRegisterWithToken(Request $request)
    {
        // Validazione dei dati di input
        $request->validate([
            'first-name' => ['required', 'string', 'max:255'],
            'last-name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'min:10', 'max:10'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        // Recupera l'invito utilizzando il token
        $invitation = \App\Models\Invitation::where('token', $request->token)->where('used', false)->first();

        if (!$invitation || $invitation->expires_at < now()) {
            throw ValidationException::withMessages(['token' => 'Invito non valido o scaduto.']);
        }

        // Creazione dell'utente
        $user = \App\Models\User::create([
            'first_name' => $request->input('first-name'),
            'last_name' => $request->input('last-name'),
            'email' => $invitation->email,
            'phone' => $request->phone,
            'status' => 'active', // Imposta lo stato dell'utente
            'invitation_token' => $invitation->token,
            'created_by' => $invitation->created_by, // ID dell'utente che ha creato l'invito
            'email_verified_at' => now(), // Imposta la verifica dell'email
            'password' => bcrypt($request->password), // Cifra la password
            'remember_token' => null, // Inizializza il token di remember
            'last_login_at' => now(), // Imposta l'ultima data di accesso
        ]);
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