<?php

namespace Canopy\Ecommerce\Forms;

use Canopy\Base\Enums\BaseStatusEnum;
use Canopy\Base\Forms\FormAbstract;
use Canopy\Ecommerce\Http\Requests\ProductCategoryRequest;
use Canopy\Ecommerce\Models\ProductCategory;
use Illuminate\Support\Arr;

class ProductCategoryForm extends FormAbstract
{

    /**
     * {@inheritDoc}
     */
    public function buildForm()
    {
        $list = get_product_categories();

        $categories = [];
        foreach ($list as $row) {
            $categories[$row->id] = $row->indent_text . ' ' . $row->name;
        }
        $categories[0] = trans('plugins/ecommerce::product-categories.none');
        $categories = Arr::sortRecursive($categories);

        $this
            ->setupModel(new ProductCategory)
            ->setValidatorClass(ProductCategoryRequest::class)
            ->withCustomFields()
            ->add('name', 'text', [
                'label'      => trans('core/base::forms.name'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'placeholder'  => trans('core/base::forms.name_placeholder'),
                    'data-counter' => 120,
                ],
            ])
            ->add('parent_id', 'select', [
                'label'      => trans('core/base::forms.parent'),
                'label_attr' => ['class' => 'control-label required'],
                'attr'       => [
                    'class' => 'select-search-full',
                ],
                'choices'    => $categories,
            ])
            ->add('description', 'editor', [
                'label'      => trans('core/base::forms.description'),
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'rows'         => 4,
                    'placeholder'  => trans('core/base::forms.description_placeholder'),
                    'data-counter' => 500,
                ],
            ])
            ->add('order', 'number', [
                'label'         => trans('core/base::forms.order'),
                'label_attr'    => ['class' => 'control-label'],
                'attr'          => [
                    'placeholder' => trans('core/base::forms.order_by_placeholder'),
                ],
                'default_value' => 0,
            ])

            ->add('color', 'customSelect', [
                'label'         => trans('plugins/ecommerce::product-categories.color'),
                'label_attr'    => ['class' => 'control-label'],
                'choices'    => [
                    'magenta' => __('Magenta'),
                    'violet' => __('Violet'),
                    'red' => __('Red'),
                    'green' => __('Green'),
                    'blue' => __('Blue'),
                ],
            ])

            ->add('status', 'customSelect', [
                'label'      => trans('core/base::tables.status'),
                'label_attr' => ['class' => 'control-label required'],
                'choices'    => BaseStatusEnum::labels(),
            ])
            ->add('image', 'mediaImage', [
                'label'      => trans('core/base::forms.image'),
                'label_attr' => ['class' => 'control-label'],
            ])
            ->add('is_featured', 'onOff', [
                'label'         => trans('core/base::forms.is_featured'),
                'label_attr'    => ['class' => 'control-label'],
                'default_value' => false,
            ])
            ->setBreakFieldPoint('status');
    }
}
