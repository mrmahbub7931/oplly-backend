<?php

namespace Canopy\Blog\Repositories\Interfaces;

use Canopy\Support\Repositories\Interfaces\RepositoryInterface;

interface TagInterface extends RepositoryInterface
{

    /**
     * @return array
     */
    public function getDataSiteMap();

    /**
     * @param int $limit
     * @return array
     */
    public function getPopularTags($limit);

    /**
     * @param bool $active
     * @return array
     */
    public function getAllTags($active = true);
}
