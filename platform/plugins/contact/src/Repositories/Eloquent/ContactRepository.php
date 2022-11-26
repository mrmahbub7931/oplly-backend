<?php

namespace Canopy\Contact\Repositories\Eloquent;

use Canopy\Contact\Enums\ContactStatusEnum;
use Canopy\Contact\Repositories\Interfaces\ContactInterface;
use Canopy\Support\Repositories\Eloquent\RepositoriesAbstract;

class ContactRepository extends RepositoriesAbstract implements ContactInterface
{
    /**
     * {@inheritDoc}
     */
    public function getUnread($select = ['*'])
    {
        $data = $this->model->where('status', ContactStatusEnum::UNREAD)->select($select)->get();
        $this->resetModel();
        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function countUnread()
    {
        $data = $this->model->where('status', ContactStatusEnum::UNREAD)->count();
        $this->resetModel();
        return $data;
    }
}