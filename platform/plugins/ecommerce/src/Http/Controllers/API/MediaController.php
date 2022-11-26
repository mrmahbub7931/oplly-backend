<?php

namespace Canopy\Ecommerce\Http\Controllers\API;

use Canopy\Base\Http\Responses\BaseHttpResponse;
use Canopy\Media\RvMedia;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function get(Request $request, BaseHttpResponse $response)
    {
        $filename = $request->input('filename', null);

        if (null === $filename) {
            return $response->setError()->setCode(404)->setMessage('Resource Not Found')->toApiResponse();
        }

        $asset = RvMedia::url($filename);

        return $response
            ->setData($asset)
            ->toApiResponse();
    }

    /**
     * @param Request          $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(Request $request, BaseHttpResponse $response)
    {
        $assetDir = $request->input('path', 'media');
        if ($request->hasFile('asset')) {
            $result = RvMedia::handleUpload($request->file('asset'), 0, $assetDir);

            if ($result['error'] != false) {
                return $response->setError()
                    ->setCode(400)
                    ->setMessage($result['message'])
                    ->toApiResponse();
            }
            $asset = $result['data'];

            return $response
                ->setData($asset)
                ->toApiResponse();
        }

        return $response->setError()
            ->setCode(400)
            ->setMessage('No File Provided')
            ->toApiResponse();
    }
}
