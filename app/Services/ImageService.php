<?php

namespace App\Services;

use App\Models\Image;

class ImageService
{
    /**
     * @var FileUploader
     */
    private $uploader;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * @param array $data
     */
    public function create(array $data)
    {
        $image = new Image();

        $this->fill($image, $data);

        $image->save();

        return $image;
    }

    /**
     * @param Image $image
     * @param array $data
     * @return Image
     */
    private function fill(Image $image, array $data)
    {
        $data['path'] = $this->uploader->upload($data['image']);

        $image->fill($data);

        $image->user()->associate($data['user']);

        return $image;
    }
}
