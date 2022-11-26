<?php

use Canopy\Table\Http\Controllers\TableController;

Route::group(['middleware' => ['web', 'core', 'auth'], 'prefix' => 'tables', 'permission' => false], function () {
    Route::get('bulk-change/data', [TableController::class, 'getDataForBulkChanges'])->name('tables.bulk-change.data');
    Route::post('bulk-change/save', [TableController::class, 'postSaveBulkChange'])->name('tables.bulk-change.save');
    Route::get('get-filter-input', [TableController::class, 'getFilterInput'])->name('tables.get-filter-input');
});