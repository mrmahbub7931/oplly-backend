<?php

namespace Canopy\Blog\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Canopy\Base\Enums\BaseStatusEnum;
use Canopy\Base\Http\Responses\BaseHttpResponse;
use Canopy\Blog\Http\Resources\TagResource;
use Canopy\Blog\Repositories\Interfaces\TagInterface;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * @var TagInterface
     */
    protected $tagRepository;

    /**
     * AuthenticationController constructor.
     *
     * @param TagInterface $tagRepository
     */
    public function __construct(TagInterface $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * List tags
     *
     * @group Blog
     *
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function index(Request $request, BaseHttpResponse $response)
    {
        $data = $this->tagRepository
            ->getModel()
            ->where(['status' => BaseStatusEnum::PUBLISHED])
            ->select(['id', 'name', 'description'])
            ->paginate($request->input('per_page', 10));

        return $response
            ->setData(TagResource::collection($data))
            ->toApiResponse();
    }
}
