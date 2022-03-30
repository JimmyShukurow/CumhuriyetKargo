<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\MainCargo\MainCargoController;
use App\Http\Controllers\Backend\MainCargo\DeliveryController;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {
    Route::group(['prefix' => 'MainCargo', 'as' => 'mainCargo.', 'middleware' => ['MainCargoMid']], function () {

        Route::get('NewCargo', [MainCargoController::class, 'newCargo'])->name('newCargo')
            ->middleware('PermissionOfCreateCargo');


        Route::get('', [MainCargoController::class, 'index'])->name('index');
        Route::get('GetCargoes', [MainCargoController::class, 'getMainCargoes'])->name('getCargoes')
            ->middleware('throttle:30,1');
        Route::post('AjaxTransactions/{transaction}', [MainCargoController::class, 'ajaxTransacrtions']);
        Route::get('StatementOfResponsibility/{ctn}', [MainCargoController::class, 'statementOfResponsibility']);

        Route::get('SearchCargo', [MainCargoController::class, 'searchCargo'])->name('search');
        Route::get('SearchGlobalCargo', [MainCargoController::class, 'getGlobalCargoes'])
            ->middleware('throttle:30,1')
            ->name('getGlobalCargoes');

        Route::get('CancelledCargoes', [MainCargoController::class, 'cancelledCargoesIndex'])
            ->name('cancelledCargoes');
        Route::get('GetCancelledCargoes', [MainCargoController::class, 'getCancelledCargoes']);


        Route::get('SearchCargoGM', [MainCargoController::class, 'searchCargoGM'])->name('SearchCargoGM');
        Route::get('SearchGlobalCargoGM', [MainCargoController::class, 'getGlobalCargoesGM'])
            ->middleware('throttle:30,1');

        Route::get('CalculateServiceFee', [MainCargoController::class, 'calculateServiceFee'])->name('calculateServiceFee');
    });


    Route::group(['prefix' => 'Delivery', 'as' => 'delivery.'], function () {
        Route::get('', [DeliveryController::class, 'index'])->name('index');
    });

});
