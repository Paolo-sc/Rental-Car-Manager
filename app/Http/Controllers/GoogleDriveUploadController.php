<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Routing\Controller as BaseController;


class GoogleDriveUploadController extends BaseController
{
    public function handleUpload(Request $request, GoogleDriveService $driveService)
    {
    $request->validate(['file' => 'required|file']);

    $uploadedFile = $request->file('file');
    $filePath = $uploadedFile->getPathname();
    $fileName = $uploadedFile->getClientOriginalName();

    try {
        $fileId = $driveService->uploadToAutonoleggio($filePath, $fileName, auth()->user());
        return back()->with('success', "File caricato su Google Drive! ID: $fileId");
    } catch (\Exception $e) {
        return back()->with('error', "Errore: " . $e->getMessage());
    }
    }
}