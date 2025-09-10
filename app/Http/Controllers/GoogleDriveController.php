<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Oauth2;
use Illuminate\Routing\Controller as BaseController;

class GoogleDriveController extends BaseController
{
    /**
     * Reindirizza l'utente a Google per l'autorizzazione
     */
    public function redirectToGooglePopup()
    {
        $client = new Client();
        $client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_DRIVE_REDIRECT_URI_POPUP'));

        // Scope per Drive e profilo
        $client->addScope([Drive::DRIVE_FILE, 'profile', 'email']);

        // Importante: access_type=offline per ottenere refresh token
        $client->setAccessType('offline');

        // Importante: prompt=consent per forzare refresh_token anche se l'utente ha già autorizzato
        $client->setPrompt('consent');

        $authUrl = $client->createAuthUrl();
        return redirect($authUrl);
    }

    /**
     * Callback dopo l'autorizzazione
     */
    public function handleGoogleCallbackPopup(Request $request)
    {
        $client = new Client();
        $client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_DRIVE_REDIRECT_URI_POPUP'));
        $client->addScope([Drive::DRIVE_FILE, 'profile', 'email']);
        $client->setAccessType('offline');
        $client->setPrompt('consent');

        // Ottieni il token dall'auth code
        $token = $client->fetchAccessTokenWithAuthCode($request->code);

        if (isset($token['error'])) {
            return response()->json([
                'error' => $token['error'],
                'message' => $token['error_description'] ?? 'Errore durante l\'autenticazione Google.'
            ], 400);
        }

        // Passa il token alla view JS (popup)
        return response()->view('google-drive-popup-callback', [
            'token' => json_encode($token),
        ]);
    }

    /**
     * Salva il token ricevuto dal popup
     */
    public function salvaTokenGoogleDrive(Request $request)
    {
        $token = $request->input('token');
        $user = auth()->user();

        // Salva il token completo, incluso refresh_token se presente
        $user->google_drive_token = json_encode($token);

        // Ottieni il nome dell'utente Drive
        $client = new Client();
        $client->setAccessToken($token);
        $oauth2 = new Oauth2($client);
        $info = $oauth2->userinfo->get();

        $user->google_drive_name = $info->name ?? $info->email ?? 'Drive';
        $user->save();

        return response()->json(['ok' => true]);
    }
}
