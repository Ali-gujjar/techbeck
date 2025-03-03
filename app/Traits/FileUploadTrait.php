<?php
namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait FileUploadTrait
{
    public function uploadFile($file, $directory): string
    {
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $path = Storage::disk('public')->putFileAs($directory, $file, $fileName);
        return Storage::url($path);
    }

    public function deleteFile($filePath): null
    {
        $path = str_replace('/storage/', '', $filePath);
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
        return null;
    }
}
