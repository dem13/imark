<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\CreateImageRequest;
use App\Http\Requests\API\UpdateImageRequest;
use App\Http\Resources\ImageResource;
use App\Services\ImageService;
use Gate;
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

    /**
     * @param $id
     * @param UpdateImageRequest $request
     * @return ImageResource
     */
    public function update($id, UpdateImageRequest $request): ImageResource
    {
        $image = $this->imageService->findById($id);

        if(!$image) {
            abort(404);
        }

        Gate::authorize('update-image', $image);

        $data = $request->validated();

        $this->imageService->update($image, $data);

        return new ImageResource($image);
    }

    /**
     * @param $id
     * @return string[]|void
     */
    public function delete($id)
    {
        $image = $this->imageService->findById($id);

        if(!$image) {
            abort(404);
        }

        Gate::authorize('delete-image', $image);

        if($this->imageService->delete($image)) {
            return [
                'message' => 'Deleted'
            ];
        }

        abort(400, 'Unable to delete');
    }
}
