<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Drive;

class GoogleDriveController extends Controller
{
    public function redirectToGooglePopup()
    {
        $client = new Client();
        $client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_DRIVE_REDIRECT_URI_POPUP'));
        $client->addScope(Drive::DRIVE_FILE);
        $client->setAccessType('offline');

        $authUrl = $client->createAuthUrl();
        return redirect($authUrl);
    }

    public function handleGoogleCallbackPopup(Request $request)
    {
        $client = new Client();
        $client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_DRIVE_REDIRECT_URI_POPUP'));
        $client->addScope(Drive::DRIVE_FILE);

        $token = $client->fetchAccessTokenWithAuthCode($request->code);

        // Mostra una pagina che comunica il token alla finestra principale e si chiude
        return response()->view('google-drive-popup-callback', [
            'token' => json_encode($token),
        ]);
    }
}