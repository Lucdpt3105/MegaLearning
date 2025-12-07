<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class GoogleDriveService
{
    /**
     * Upload a file to Google Drive.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path
     * @return string|false
     */
    public function uploadFile($file, $path = '')
    {
        try {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $path ? $path . '/' . $fileName : $fileName;

            Storage::disk('google')->put($filePath, file_get_contents($file));

            return $filePath;
        } catch (\Exception $e) {
            \Log::error('Google Drive Upload Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get the URL of a file on Google Drive.
     *
     * @param string $path
     * @return string
     */
    public function getUrl($path)
    {
        return Storage::disk('google')->url($path);
    }

    /**
     * List files in a directory.
     *
     * @param string $directory
     * @return array
     */
    public function listFiles($directory = '')
    {
        return Storage::disk('google')->files($directory);
    }

    /**
     * Delete a file from Google Drive.
     *
     * @param string $path
     * @return bool
     */
    public function deleteFile($path)
    {
        return Storage::disk('google')->delete($path);
    }
}
