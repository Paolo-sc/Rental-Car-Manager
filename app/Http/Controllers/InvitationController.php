<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Invitation;
use App\Mail\InvitationMail;
use Illuminate\Support\Facades\Mail;

class InvitationController extends BaseController
{
    // Mostra la view per la gestione degli inviti
    public function index()
    {
        // Recupera tutti gli inviti non utilizzati
        $invitations = Invitation::where('used', false)->get();

        // Ritorna la view con gli inviti
        return view('invitations', compact('invitations'));
    }

    public function doInvite(Request $request)
    {
        // Validazione dei dati di input
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);
        // Recupera l'email dall'input
        $email = $request->input('email');

        // Controlla se esiste già un invito non utilizzato per questa email
        $existingInvitation = Invitation::where('email', $email)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if ($existingInvitation) {
            return redirect()->back()->with('warning', 'Un invito è già stato inviato a questo indirizzo email.');
        }
        // Genera un token casuale per l'invito
        $token = bin2hex(random_bytes(32));

        // Salva l'invito nel database
        $invitation =Invitation::create([
            'email' => $email,
            'token' => $token,
            'used' => false,
            'created_by' => auth()->id(), // ID dell'utente che ha creato l'invito
            'expires_at' => now()->addDays(2) // Imposta la scadenza a 2 giorni
        ]);

        // Logica per inviare l'invito via email
        try{
            //Invia l'email di invito
            Mail::to($email)->send(new InvitationMail($invitation));

            //Reindirizza l'utente con un messaggio di successo
            return redirect()->back()->with('success', 'Invito inviato con successo!');
        }catch (\Exception $e) {
            // Se l'invio email fallisce, elimina l'invito dal database
            $invitation->delete();
            return redirect()->back()->with('error', 'Errore nell\'invio dell\'invito: ' . $e->getMessage());
        }
    }
}