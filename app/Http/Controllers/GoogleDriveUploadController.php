<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Routing\Controller as BaseController;


class GoogleDriveUploadController extends BaseController
{
    //Funzione per upload su Drive
    public function uploadToAutonoleggio($filePath, $fileName)
    {
        $user = auth()->user();
        if (!$user || !$user->google_drive_token) {
            //return redirect()->route('google.drive.popup')->with('error', 'Devi collegare il tuo account Google Drive prima di caricare file.');
            throw new \Exception('Devi collegare il tuo account Google Drive prima di caricare file.');
        }

        $token = json_decode($user->google_drive_token, true);

        $client = new Client();
        $client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
        $client->setAccessToken($token);

        // Refresh token if needed
        if ($client->isAccessTokenExpired()) {
            if (isset($token['refresh_token'])) {
                $client->fetchAccessTokenWithRefreshToken($token['refresh_token']);
                $user->google_drive_token = json_encode($client->getAccessToken());
                $user->save();
            } else {
                //return redirect()->route('google.drive.popup')->with('error', 'Il token di accesso è scaduto. Devi ricollegare il tuo account Google Drive.');
                throw new \Exception('Il token di accesso è scaduto. Devi ricollegare il tuo account Google Drive.');
            }
        }

        $driveService = new Drive($client);

        // Cerca la cartella "Autonoleggio"
        $folders = $driveService->files->listFiles([
            'q' => "mimeType='application/vnd.google-apps.folder' and name='Autonoleggio' and trashed=false",
            'fields' => 'files(id, name)',
            'spaces' => 'drive'
        ]);
        $folderId = null;
        if (count($folders->getFiles()) > 0) {
            $folderId = $folders->getFiles()[0]->getId();
        } else {
            // Crea la cartella
            $folderMetadata = new Drive\DriveFile([
                'name' => 'Autonoleggio',
                'mimeType' => 'application/vnd.google-apps.folder'
            ]);
            $folder = $driveService->files->create($folderMetadata, [
                'fields' => 'id'
            ]);
            $folderId = $folder->id;
        }


       // Carica il file
        $fileMetadata = new Drive\DriveFile([
            'name' => $fileName,
            'parents' => [$folderId]
        ]);
        $content = file_get_contents($filePath);

        $file = $driveService->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => mime_content_type($filePath),
            'uploadType' => 'multipart',
            'fields' => 'id'
        ]);

        return $file->id;
    }

    public function handleUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        $uploadedFile = $request->file('file');
        $filePath = $uploadedFile->getPathname();
        $fileName = $uploadedFile->getClientOriginalName();

        try {
            $fileId = $this->uploadToAutonoleggio($filePath, $fileName);
            return back()->with('success', "File caricato su Google Drive! ID: $fileId");
        } catch (\Exception $e) {
            return back()->with('error', "Errore: " . $e->getMessage());
        }
    }

}
