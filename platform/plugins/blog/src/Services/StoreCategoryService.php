<?php

namespace Canopy\Blog\Services;

use Canopy\Blog\Models\Post;
use Canopy\Blog\Services\Abstracts\StoreCategoryServiceAbstract;
use Illuminate\Http\Request;

class StoreCategoryService extends StoreCategoryServiceAbstract
{

    /**
     * @param Request $request
     * @param Post $post
     * @return mixed|void
     */
    public function execute(Request $request, Post $post)
    {
        $categories = $request->input('categories');
        if (!empty($categories)) {
            $post->categories()->detach();
            foreach ($categories as $category) {
                $post->categories()->attach($category);
            }
        }
    }
}
