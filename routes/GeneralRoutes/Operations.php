<?php

use App\Http\Controllers\Backend\Operation\LocalLocationController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'Operation'], function () {
    Route::group(['middleware' => 'LocalLocationMid'], function () {
        Route::resource('LocalLocation', LocalLocationController::class);
        Route::get('LocationLocationReport', [LocalLocationController::class, 'locationReport'])->name('location.report');
        Route::get('LocalLocationGetTrGeneralLocations', [LocalLocationController::class, 'GetTrGeneralLocations'])
            ->name('location.GetTrGeneralLocations');
        Route::get('LocalLocationGetAgencyLocationStatus', [LocalLocationController::class, 'getAgencyLocationStatus'])
            ->name('location.getAgencyLocationStatus');
        Route::post('LocalLocationGetAgencyLocations', [LocalLocationController::class, 'getAgencyLocations'])
            ->name('location.getAgencyLocations');

        Route::get('GetLocations', [LocalLocationController::class, 'getLocation'])->name('operation.getLocation');
        Route::post('GetNeighborhoodsOfAgency', [LocalLocationController::class, 'getNeighborhoodsOfAgency']);
    });
});
