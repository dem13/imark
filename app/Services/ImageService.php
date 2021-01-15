<?php

namespace App\Services;

use App\Models\Image;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
     * @param $options
     * @return LengthAwarePaginator
     */
    public function find($options): LengthAwarePaginator
    {
        $q = Image::query();

        if (!empty($options['user_id'])) {
            $q->where('user_id', $options['user_id']);
        } elseif (!empty($options['withUsers'])) {
            $q->leftJoin('users', 'images.user_id', '=', 'users.id')
                ->select('images.*', 'users.first_name', 'users.last_name');
        }

        $q->orderBy('created_at', ($options['sort_created_at'] ?? -1) === 1 ? 'asc' : 'desc');

        return $q->paginate();
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
