<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Storage;

class FileUploader
{
    public function upload(UploadedFile $file)
    {
        return Storage::disk('public')->putFile('uploads', $file);
    }
}
