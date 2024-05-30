<?php

namespace App\Services;

use Google_Client;
use Google_Service_Drive;

class GoogleDriveService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setAuthConfig(env('GOOGLE_DRIVE_SERVICE_ACCOUNT_PATH'));
        $this->client->setScopes([Google_Service_Drive::DRIVE]);
    }

    public function uploadFile($filePath, $folderId)
    {
        $service = new Google_Service_Drive($this->client);

        $fileMetadata = new \Google_Service_Drive_DriveFile([
            'name' => basename($filePath),
            'parents' => [$folderId],
        ]);

        $content = file_get_contents($filePath);
        $file = $service->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => 'application/octet-stream',
            'uploadType' => 'multipart',
            'fields' => 'id',
        ]);

        return $file->id;
    }

    public function uploadFileContent($fileContent, $folderId, $fileName)
    {
        $service = new Google_Service_Drive($this->client);

        $fileMetadata = new \Google_Service_Drive_DriveFile([
            'name' => $fileName,
            'parents' => [$folderId],
        ]);

        $file = $service->files->create($fileMetadata, [
            'data' => $fileContent,
            'mimeType' => 'application/octet-stream',
            'uploadType' => 'multipart',
            'fields' => 'id',
        ]);

        return $file->id;
    }

    public function removeFile($fileId, $folderId) {
        
        $service = new Google_Service_Drive($this->client);

        try {
            // Retrieve the file's metadata
            $file = $service->files->get($fileId, ['fields' => 'parents']);
            
            // Check if the file belongs to the specified folder
            if (in_array($folderId, $file->parents)) {
                // If the file belongs to the specified folder, delete it
                $service->files->delete($fileId);
                return true; // File deleted successfully
            } else {
                return false; // File does not belong to the specified folder
            }
        } catch (\Exception $e) {
            return false; // Error occurred while deleting file
        }
    }
}
