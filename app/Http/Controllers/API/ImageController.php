<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\CreateImageRequest;
use App\Http\Resources\ImageResource;
use App\Services\ImageService;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * @var ImageService
     */
    private $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * @param CreateImageRequest $request
     * @return ImageResource
     */
    public function store(CreateImageRequest $request): ImageResource
    {
        $data = $request->validated();

        $data['user'] = $request->user();

        $image = $this->imageService->create($data);

        return new ImageResource($image);
    }
}
