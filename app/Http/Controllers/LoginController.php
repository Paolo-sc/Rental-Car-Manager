<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
/**
 * Class Controller
 *
 * This is the base controller class for the application.
 * It extends the BaseController provided by Laravel.
 */

class LoginController extends BaseController
{
    public function showLoginForm()
    {
        // Mostra il modulo di login
        return view('login');
    }

    public function dashboard()
    {
        // Mostra lo user autenticato
        return auth()->user();
    }

    public function doLogout()
    {
        // Effettua il logout dell'utente
        auth()->logout();
        // Reindirizza l'utente alla pagina di login
        return redirect('login');
    }

    public function doLogin(Request $request)
    {
        // Validazione dei dati di input
        $request->validate([
            'email' => ['required', 'string', 'email'], // 'required': il campo non deve essere vuoto. 'string': deve essere una stringa. 'email': deve essere un formato email valido (es. "utente@dominio.com").
            'password' => ['required', 'string'],       // 'required': il campo non deve essere vuoto. 'string': deve essere una stringa.
        ]);
        // Tentativo di autenticazione
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember'); // Recupera il valore del checkbox 'remember me'
        if (auth()->attempt($credentials,$remember)) {
            // Autenticazione riuscita, reindirizza l'utente alla dashboard
            $user = auth()->user();
            $user->last_login_at = now(); // Aggiorna il campo 'last_login_at' con la data e ora attuale
            $user->save(); // Salva le modifiche nel database
            return redirect()->intended('dashboard');
        }
        // Autenticazione fallita, reindirizza l'utente indietro con un messaggio di errore
        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }
}