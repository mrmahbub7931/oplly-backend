<?php

namespace Canopy\Ecommerce\Http\Controllers;

use Canopy\Base\Forms\FormBuilder;
use Canopy\Base\Http\Controllers\BaseController;
use Canopy\Ecommerce\Repositories\Interfaces\RegionInterface;
use Canopy\Ecommerce\Tables\RegionTable;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Throwable;

class RegionController extends BaseController
{
    private $regionRepository;

    public function __construct(RegionInterface $regionRepository)
    {
        $this->regionRepository = $regionRepository;
    }

    /**
     * @param RegionTable $dataTable
     * @return Factory|View
     * @throws Throwable
     */
    public function index(RegionTable $dataTable)
    {
        page_title()->setTitle('Regions');
        return $dataTable->renderTable();
    }

    public function create()
    {
        // TODO Implement feature
        return true;
    }

    /**
     * @param int $id
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit(int $id, FormBuilder $formBuilder)
    {
        $record = $this->regionRepository->findOrFail($id);
        // page_title()->setTitle(trans('plugins/ecommerce::talent.edit', ['name' => $talent->name]));

        return ''; //$formBuilder->create(RegionForm::class, ['model' => $region])->renderForm();
    }

}
