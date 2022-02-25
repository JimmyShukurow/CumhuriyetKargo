<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Operation\VariousController;
use App\Http\Controllers\TransferCarsController;
use App\Http\Controllers\AgencyTransferCarsController;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {

    Route::resource('VariousCars', VariousController::class)
        ->middleware('TransferCarsMid');

    Route::resource('TransferCars', TransferCarsController::class)
        ->middleware('TransferCarsMid');

    Route::group(['middleware' => 'TransferCarsMid'], function () {
        Route::get('AllTransferCarsData', [TransferCarsController::class, 'allData'])->name('transfer.car.all');
        Route::post('GetTransferCar', [TransferCarsController::class, 'getTransferCar'])->name('getTransferCars');
    });
    Route::get('VariousCarsGetCars', [VariousController::class, 'getCars'])->name('VariousCars.getCars');

    Route::resource('AgencyTransferCars', AgencyTransferCarsController::class);
    Route::get('AllAgencyTransferCars', [AgencyTransferCarsController::class, 'allData'])->name('agency.transfer.car.all');
    Route::post('GetAgencyTransferCar', [AgencyTransferCarsController::class, 'getAgencyTransferCar'])->name('getAgencyTransferCar');
});
