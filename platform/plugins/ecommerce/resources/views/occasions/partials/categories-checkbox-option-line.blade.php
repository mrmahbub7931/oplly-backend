<?php
/**
* @var string $value
*/
$value = isset($value) ? (array) $value : []; ?>
@if ($occasions)
    <ul>
        @foreach ($occasions as $category)
            @if ($category->id != $currentId)
                <li value="{{ $category->id ?? '' }}" {{ $category->id == $value ? 'selected' : '' }}>
                    {!! Form::customCheckbox([[$name, $category->id, $category->name, in_array($category->id, $value)]]) !!}
                    @include('plugins/ecommerce::occasions.partials.occasions-checkbox-option-line', [
                    'occasions' => $category->child_cats,
                    'value' => $value,
                    'currentId' => $currentId,
                    'name' => $name
                    ])
                </li>
            @endif
        @endforeach
    </ul>
@endif
