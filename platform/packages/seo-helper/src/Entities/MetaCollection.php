<?php

namespace Canopy\SeoHelper\Entities;

use Canopy\SeoHelper\Bases\MetaCollection as BaseMetaCollection;
use Canopy\SeoHelper\Helpers\Meta;

class MetaCollection extends BaseMetaCollection
{
    /**
     * Ignored tags, they have dedicated class.
     *
     * @var array
     */
    protected $ignored = [
        'description',
    ];

    /**
     * Add a meta to collection.
     *
     * @param string $name
     * @param string $content
     *
     * @return MetaCollection
     */
    public function add($item)
    {
        $meta = Meta::make($item['name'], $item['content']);

        if ($meta->isValid() && !$this->isIgnored($item['name'])) {
            $this->put($meta->key(), $meta);
        }

        return $this;
    }
}
