<?php

namespace Canopy\Ecommerce\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Canopy\Base\Enums\BaseStatusEnum;
use Canopy\Base\Http\Responses\BaseHttpResponse;
use Canopy\Ecommerce\Http\Requests\OccasionRequest;
use Canopy\Ecommerce\Http\Resources\OccassionResource;
use Canopy\Ecommerce\Repositories\Interfaces\OccasionInterface;
use Illuminate\Http\Request;

class OccasionController extends Controller
{
    /**
     * @var OccasionInterface
     */
    protected $occasionRepository;

    /**
     * OccasionController constructor.
     * @param OccasionInterface $occasionRepository
     */
    public function __construct(OccasionInterface $occasionRepository)
    {
        $this->occasionRepository = $occasionRepository;
    }

    /**
     * @param \Illuminate\Http\Request                     $request
     * @param \Canopy\Base\Http\Responses\BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function index(Request $request, BaseHttpResponse $response)
    {
        $data = $this->occasionRepository
            ->getModel()
            ->where(['status' => BaseStatusEnum::PUBLISHED])
            ->select(['id', 'name', 'image', 'description', 'show_standard', 'show_business'])->get();

        return $response
            ->setData(OccassionResource::collection($data))
            ->toApiResponse();
    }

    /**
     * @param int $id
     * @param OccasionRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function get($id, OccasionRequest $request, BaseHttpResponse $response)
    {
        $occassion = $this->occasionRepository->getModel()
            ->where('id', $id)
            ->where('status', BaseStatusEnum::PUBLISHED)
            ->select(['id', 'name', 'description', 'show_standard', 'show_business']);
        if (!$occassion) {
            return $response->setError()->setCode(404)->setMessage('Not found')->toApiResponse();
        }
        return $response
            ->setData(new OccassionResource($occassion))
            ->toApiResponse();
    }
}
