<?php

namespace Canopy\MobileApp\Http\Controllers;

use Canopy\Base\Events\DeletedContentEvent;
use Canopy\Base\Http\Controllers\BaseController;
use Canopy\Base\Http\Responses\BaseHttpResponse;
use Canopy\Base\Traits\HasDeleteManyItemsTrait;
use Canopy\MobileApp\Repositories\Interfaces\MobileAppInterface;
use Exception;
use Illuminate\Http\Request;
use Assets;

class MobileAppController extends BaseController
{
    use HasDeleteManyItemsTrait;

    /**
     * @var MobileAppInterface
     */
    protected $mobileAppRepository;

    /**
     * MobileAppController constructor.
     * @param MobileAppInterface $mobileAppRepository
     */
    public function __construct(MobileAppInterface $mobileAppRepository)
    {
        $this->mobileAppRepository = $mobileAppRepository;
    }


    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSettings()
    {
        page_title()->setTitle(trans('plugins/mobileapp::mobileapp.name'));

        Assets::addStylesDirectly([
            'vendor/core/core/base/libraries/codemirror/lib/codemirror.css',
            'vendor/core/core/base/libraries/codemirror/addon/hint/show-hint.css'
        ])
        ->addScriptsDirectly([
            'vendor/core/core/base/libraries/codemirror/lib/codemirror.js',
            'vendor/core/core/base/libraries/codemirror/lib/css.js',
            'vendor/core/core/base/libraries/codemirror/addon/hint/show-hint.js',
            'vendor/core/core/base/libraries/codemirror/addon/hint/anyword-hint.js',
            'vendor/core/core/base/libraries/codemirror/addon/hint/css-hint.js'
        ]);


        $setting = $this->mobileAppRepository->getFirstBy(['id' => 1]);

        return view('plugins/mobileapp::settings.index', ['setting' => $setting]);
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function postSettings(
        Request $request,
        BaseHttpResponse $response
    ) {

        $primaryStore = $this->mobileAppRepository->getFirstBy(['id' => 1]);

        if (!$primaryStore) {
            $primaryStore = $this->mobileAppRepository->getModel();
            $primaryStore->version = $request->input('version');
            $primaryStore->platform = $request->input('platform');
            $primaryStore->homepage = json_encode($request->input('homepage', []));
            $primaryStore->homepage_talent = json_encode($request->input('homepage_talent', []));
            $primaryStore->allow_push = $request->input('allow_push', 1);
            $primaryStore->allow_feed = $request->input('allow_feed', 1);
            $primaryStore->allow_live = $request->input('allow_live', 0);
            $primaryStore->allow_causes = $request->input('allow_causes', 1);
            $this->mobileAppRepository->createOrUpdate($primaryStore, ['id' => 1]);
        } else {
            $this->mobileAppRepository->createOrUpdate($request->input(), ['id' => 1]);
        }



        return $response
            ->setNextUrl(route('mobileapp.settings'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }
}
