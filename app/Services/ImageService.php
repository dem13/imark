<?php

namespace App\Services;

use App\Models\Image;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Storage;

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
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function find(array $options): LengthAwarePaginator
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
     * @param $id
     * @return Image|null
     */
    public function findById($id): ?Image
    {
        return Image::find($id);
    }

    /**
     *
     * @param array $data
     * @return Image
     */
    public function create(array $data): Image
    {
        $image = new Image();

        $this->fill($image, $data);

        $image->save();

        return $image;
    }

    /**
     * @param Image $image
     * @param array $data
     * @return bool
     */
    public function update(Image $image, array $data): bool
    {
        $this->fill($image, $data);

        return $image->save();
    }

    /**
     * @param Image $image
     * @return bool|null
     */
    public function delete(Image $image): ?bool
    {
        try {
            $deleted = $image->delete();

            Storage::disk('public')->delete($image->path);

            return $deleted;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param Image $image
     * @param array $data
     * @return Image
     */
    private function fill(Image $image, array $data): Image
    {
        if(isset($data['image'])) {
            $data['path'] = $this->uploader->upload($data['image']);
        }

        $image->fill($data);

        if($data['user'] ?? false) {
            $image->user()->associate($data['user']);
        }

        return $image;
    }
}
