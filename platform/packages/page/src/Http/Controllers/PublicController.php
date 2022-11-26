<?php

namespace Canopy\Page\Http\Controllers;

use Canopy\Page\Models\Page;
use Canopy\Page\Services\PageService;
use Illuminate\Routing\Controller;
use Response;
use SlugHelper;
use Theme;

class PublicController extends Controller
{
    /**
     * @param string $slug
     * @param PageService $pageService
     * @return Response
     */
    public function getPage($slug, PageService $pageService)
    {
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Page::class));

        if (!$slug) {
            abort(404);
        }

        $data = $pageService->handleFrontRoutes($slug);

        return Theme::scope($data['view'], $data['data'], $data['default_view'])->render();
    }
}
