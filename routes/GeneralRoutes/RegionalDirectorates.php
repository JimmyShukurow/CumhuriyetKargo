<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\RegionalDirectorate\RDController;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {
    Route::group(['prefix' => '/RegionalDirectorates', 'middleware' => 'RegionalDirectoratesMid', 'as' => 'rd.'], function () {

        Route::get('/', [RDController::class, 'index'])->name('Index');
        Route::get('AddRD', [RDController::class, 'addRd'])->name('addRd');
        Route::post('AddRD', [RDController::class, 'insertRd'])->name('InsertRd');
        Route::post('DestroyRD', [RDController::class, 'dersroyRd']);
        Route::get('EditRd/{id}', [RDController::class, 'editRd'])->name('EditRd');
        Route::post('EditRd/{id}', [RDController::class, 'updateRd'])->name('UpdateRd');
        Route::get('RegionDistrict/{id?}', [RDController::class, 'regionDistrict'])->name('RegionDistrict');
        Route::get('Report', [RDController::class, 'getReport'])->name('Report');

        ### ==> Ajax Info <== ###
        Route::post('Info', [RDController::class, 'regionInfo'])->name('Info');
        Route::post('RegionalDistricts', [RDController::class, 'regionalDistricts'])->name('RegionalDistricts');
        Route::post('AddRegDistrict', [RDController::class, 'addRegDistrict'])->name('AddRegDistrict');
        Route::get('ListRegionalDistricts', [RDController::class, 'listRegionalDistricts'])->name('ListRegionalDistricts');
        Route::get('ListIdleDistricts', [RDController::class, 'listIdleDistricts'])->name('ListIdleDistricts');
        Route::get('ListIdleAgenciesRegion', [RDController::class, 'listIdleAgenciesRegion'])->name('ListIdleAgenciesRegion');
        Route::get('ListIdleAgenciesTC', [RDController::class, 'listIdleAgenciesTc'])->name('ListIdleAgenciesTC');
        Route::post('DestroyRDDistrict', [RDController::class, 'destroyRdDistrict']);
    });
});
