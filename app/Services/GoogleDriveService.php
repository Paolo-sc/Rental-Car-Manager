<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Exception;

class GoogleDriveService
{
    public function uploadToAutonoleggio($filePath, $fileName, $user)
    {
        if (!$user || !$user->google_drive_token) {
            throw new Exception('Devi collegare il tuo account Google Drive prima di caricare file.');
        }

        $token = json_decode($user->google_drive_token, true);

        $client = new Client();
        $client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
        $client->setAccessToken($token);

        if ($client->isAccessTokenExpired()) {
            if (isset($token['refresh_token'])) {
                $client->fetchAccessTokenWithRefreshToken($token['refresh_token']);
                $user->google_drive_token = json_encode($client->getAccessToken());
                $user->save();
            } else {
                throw new Exception('Il token di accesso è scaduto. Devi ricollegare il tuo account Google Drive.');
            }
        }

        $driveService = new Drive($client);

        $folders = $driveService->files->listFiles([
            'q' => "mimeType='application/vnd.google-apps.folder' and name='Autonoleggio' and trashed=false",
            'fields' => 'files(id, name)',
            'spaces' => 'drive'
        ]);

        $folderId = count($folders->getFiles()) > 0
            ? $folders->getFiles()[0]->getId()
            : $driveService->files->create(
                new Drive\DriveFile([
                    'name' => 'Autonoleggio',
                    'mimeType' => 'application/vnd.google-apps.folder'
                ]),
                ['fields' => 'id']
              )->id;

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

        return [
            'id' => $file->id,
            'url' => "https://drive.google.com/file/d/{$file->id}/view"
        ];
    }

    public function deleteFile($fileId, $user)
    {
        if (!$user || !$user->google_drive_token) {
            throw new Exception('Devi collegare il tuo account Google Drive prima di cancellare file.');
        }

        $token = json_decode($user->google_drive_token, true);

        $client = new Client();
        $client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
        $client->setAccessToken($token);

        if ($client->isAccessTokenExpired()) {
            if (isset($token['refresh_token'])) {
                $client->fetchAccessTokenWithRefreshToken($token['refresh_token']);
                $user->google_drive_token = json_encode($client->getAccessToken());
                $user->save();
            } else {
                throw new Exception('Il token di accesso è scaduto. Devi ricollegare il tuo account Google Drive.');
            }
        }

        $driveService = new Drive($client);

        try {
            $driveService->files->delete($fileId);
            return true;
        } catch (\Google\Service\Exception $e) {
            // se il file è già stato cancellato o non esiste, eviti che esploda tutto
            if ($e->getCode() == 404) {
                return false;
            }
            throw $e;
        }
    }
}