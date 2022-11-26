<?php

namespace Canopy\Ecommerce\Http\Controllers;

use Canopy\Base\Forms\FormBuilder;
use Canopy\Base\Http\Controllers\BaseController;
use Canopy\Ecommerce\Repositories\Interfaces\WithdrawalInterface;
use Canopy\Ecommerce\Tables\WithdrawalTable;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Throwable;

class WithdrawalController extends BaseController
{
    private $withdrawalRepository;

    public function __construct(WithdrawalInterface $withdrawalRepository)
    {
        $this->withdrawalRepository = $withdrawalRepository;
    }

    /**
     * @param WithdrawalTable $dataTable
     * @return Factory|View
     * @throws Throwable
     */
    public function index(WithdrawalTable $dataTable)
    {
        page_title()->setTitle('Withdrawals');

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
        $record = $this->withdrawalRepository->findOrFail($id);
        // page_title()->setTitle(trans('plugins/ecommerce::talent.edit', ['name' => $talent->name]));

        return ''; //$formBuilder->create(WithdrawalForm::class, ['model' => $withdrawal])->renderForm();
    }
}
