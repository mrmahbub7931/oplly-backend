<?php

namespace Canopy\Widget\Repositories\Eloquent;

use Canopy\Support\Repositories\Eloquent\RepositoriesAbstract;
use Canopy\Widget\Repositories\Interfaces\WidgetInterface;

class WidgetRepository extends RepositoriesAbstract implements WidgetInterface
{
    /**
     * {@inheritDoc}
     */
    public function getByTheme($theme)
    {
        $data = $this->model->where('theme', $theme)->get();
        $this->resetModel();

        return $data;
    }
}
