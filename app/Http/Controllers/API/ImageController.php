<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\CreateImageRequest;
use App\Http\Resources\ImageResource;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return ImageResource::collection($this->imageService->find([
            'user_id' => $request->input('user_id'),
            'sort_created_at' => (int)$request->input('sort_created_at'),
            'withUsers' => true,
        ]));
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
