<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Exception;

class GoogleDriveService
{
    /**
     * Carica un file su Google Drive nella cartella Autonoleggio, con eventuale sottocartella
     */
    public function uploadToAutonoleggio($filePath, $fileName, $user, $subFolderName = null)
    {
        if (!$user || !$user->google_drive_token) {
            throw new Exception('Devi collegare il tuo account Google Drive prima di caricare file.');
        }

        $token = json_decode($user->google_drive_token, true);

        $client = new Client();
        $client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
        $client->setAccessToken($token);

        // Se il token è scaduto, prova a fare refresh
        if ($client->isAccessTokenExpired()) {
            if (isset($token['refresh_token'])) {
                $client->fetchAccessTokenWithRefreshToken($token['refresh_token']);
                $user->google_drive_token = json_encode($client->getAccessToken());
                $user->save();
            } else {
                throw new Exception('Il token di accesso è scaduto o revocato. Devi ricollegare il tuo account Google Drive.');
            }
        }

        $driveService = new Drive($client);

        // Trova o crea la cartella Autonoleggio
        $folderId = $this->findOrCreateFolder($driveService, 'Autonoleggio');

        // Se c'è una sottocartella, la trova o la crea
        if ($subFolderName) {
            $folderId = $this->findOrCreateFolder($driveService, $subFolderName, $folderId);
        }

        // Upload del file
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

    private function findOrCreateFolder(Drive $driveService, string $folderName, string $parentId = null)
    {
        $q = "mimeType='application/vnd.google-apps.folder' and name='$folderName' and trashed=false";
        if ($parentId) $q .= " and '{$parentId}' in parents";

        $folders = $driveService->files->listFiles([
            'q' => $q,
            'fields' => 'files(id, name)',
            'spaces' => 'drive'
        ]);

        if (count($folders->getFiles()) > 0) {
            return $folders->getFiles()[0]->getId();
        }

        $fileMetadata = new Drive\DriveFile([
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => $parentId ? [$parentId] : []
        ]);

        $folder = $driveService->files->create($fileMetadata, ['fields' => 'id']);
        return $folder->id;
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
            if ($e->getCode() == 404) return false;
            throw $e;
        }
    }

    public function renameFile($fileId, $newBaseName, $user)
    {
        if (!$user || !$user->google_drive_token) {
            throw new Exception('Devi collegare il tuo account Google Drive prima di rinominare file.');
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

        $file = $driveService->files->get($fileId, ['fields' => 'name']);
        $oldName = $file->name;
        $extension = pathinfo($oldName, PATHINFO_EXTENSION);

        $newName = $newBaseName . '.' . $extension;

        $fileMetadata = new Drive\DriveFile(['name' => $newName]);
        $updatedFile = $driveService->files->update($fileId, $fileMetadata, ['fields' => 'id, name']);

        return [
            'id' => $updatedFile->id,
            'name' => $updatedFile->name,
            'url' => "https://drive.google.com/file/d/{$updatedFile->id}/view"
        ];
    }

    public function replaceFile($fileId, $filePath, $fileName, $user)
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

        $fileMetadata = new Drive\DriveFile(['name' => $fileName]);
        $content = file_get_contents($filePath);

        $file = $driveService->files->update(
            $fileId,
            $fileMetadata,
            [
                'data' => $content,
                'mimeType' => mime_content_type($filePath),
                'uploadType' => 'multipart',
                'fields' => 'id'
            ]
        );

        return [
            'id' => $file->id,
            'url' => "https://drive.google.com/file/d/{$file->id}/view"
        ];
    }

    /**
     * Funzione addReservation integrata con generazione PDF e upload Drive
     */
    public function addReservation($reservation, $pdfContent, $user)
    {
        $filename = 'contratto_'.$reservation->id.'.pdf';
        $file = $this->uploadPdfFromMemory($pdfContent, $filename, $user, 'Contratti');

        $reservation->update([
            'drive_file_id' => $file['id'],
            'drive_file_url' => $file['url'],
        ]);

        return $reservation;
    }

    /**
     * Helper: upload PDF direttamente dalla memoria
     */
    public function uploadPdfFromMemory($pdfContent, $fileName, $user, $subFolderName = null)
    {
        $tempPath = tempnam(sys_get_temp_dir(), 'pdf');
        file_put_contents($tempPath, $pdfContent);

        try {
            return $this->uploadToAutonoleggio($tempPath, $fileName, $user, $subFolderName);
        } finally {
            if (file_exists($tempPath)) unlink($tempPath);
        }
    }
}
