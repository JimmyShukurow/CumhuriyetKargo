<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Region\RegionController;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {

    Route::group(['prefix' => 'Region', 'as' => 'region.'], function () {
        Route::get('Situation', [RegionController::class, 'situationIndex'])->name('situationIndex');
        Route::get('RelationPlaces', [RegionController::class, 'relationPlaces'])->name('relationPlaces');
        Route::any('AjaxTransactions/{val}', [RegionController::class, 'ajaxTransactions']);
    });
});
