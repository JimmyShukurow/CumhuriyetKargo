<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\MainCargo\MainCargoController;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {
    Route::group(['prefix' => 'MainCargo', 'as' => 'mainCargo.'], function () {
        Route::get('NewCargo', [MainCargoController::class, 'newCargo'])->name('newCargo');
        Route::get('', [MainCargoController::class, 'index'])->name('index');
        Route::get('GetCargoes', [MainCargoController::class, 'getMainCargoes'])->name('getCargoes')
            ->middleware('throttle:20,1');
        Route::post('AjaxTransactions/{transaction}', [MainCargoController::class, 'ajaxTransacrtions']);
        Route::get('StatementOfResponsibility/{ctn}', [MainCargoController::class, 'statementOfResponsibility']);

        Route::get('SearchCargo', [MainCargoController::class, 'searchCargo'])->name('search');
        Route::get('SearchGlobalCargo', [MainCargoController::class, 'getGlobalCargoes'])
            ->middleware('throttle:30,1')
            ->name('getGlobalCargoes');

        Route::get('CancelledCargoes', [MainCargoController::class, 'cancelledCargoesIndex'])
            ->name('cancelledCargoes');
        Route::get('GetCancelledCargoes', [MainCargoController::class, 'getCancelledCargoes']);
    });
});
