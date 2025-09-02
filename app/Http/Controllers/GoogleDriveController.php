<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Oauth2;
use Illuminate\Routing\Controller as BaseController;

class GoogleDriveController extends BaseController
{
    public function redirectToGooglePopup()
    {
        $client = new Client();
        $client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_DRIVE_REDIRECT_URI_POPUP'));
        $client->addScope(Drive::DRIVE_FILE);
        $client->addScope('profile');
        $client->addScope('email');
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
        $client->addScope('profile');
        $client->addScope('email');

        $token = $client->fetchAccessTokenWithAuthCode($request->code);

        // Mostra una pagina che comunica il token JS alla finestra principale e si chiude
        return response()->view('google-drive-popup-callback', [
            'token' => json_encode($token),
        ]);
    }

    public function salvaTokenGoogleDrive(Request $request)
    {
        $token = $request->input('token');
        $user = auth()->user();

        // Salva il token
        $user->google_drive_token = json_encode($token);

        // Ottieni il nome Drive dal profilo Google
        $client = new Client();
        $client->setAccessToken($token);

        $oauth2 = new Oauth2($client);
        $info = $oauth2->userinfo->get();

        $user->google_drive_name = $info->name ?? $info->email ?? 'Drive';
        $user->save();

        return response()->json(['ok' => true]);
    }
}