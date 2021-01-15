<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class FileUploader
{
    public function upload(UploadedFile $file)
    {
        $path = $file->store('uploads');

        return $path;
    }
}
