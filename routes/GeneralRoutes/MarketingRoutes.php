<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Marketing\SenderCurrentController;
use App\Http\Controllers\Backend\Marketing\PriceDraftsController;
use App\Http\Controllers\Backend\Marketing\ServiceFee\AdditionalServicesController;
use App\Http\Controllers\Backend\Marketing\ServiceFee\DesiListController;
use App\Http\Controllers\Backend\Marketing\ServiceFee\ServiceFeeController;
use App\Http\Controllers\Backend\Marketing\DistanceController;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {

    Route::group(['prefix' => 'Marketing'], function () {


        Route::group(['middleware' => 'SenderCurrentsMid'], function () {
            Route::group(['prefix' => 'SenderCurrents', 'as' => 'senderCurrents.'], function () {
                Route::post('AjaxTransaction/{transaction}', [SenderCurrentController::class, 'ajaxTransaction']);
                Route::get('GetCurrents', [SenderCurrentController::class, 'getCurrents'])->name('getCurrents');
                Route::get('CurrentContract/{CurrentCode}', [SenderCurrentController::class, 'printCurrentContract']);
            });
            Route::resource('SenderCurrents', SenderCurrentController::class);
        });

        Route::group(['middleware' => 'GeneralServicesFeeMid'], function () {
            Route::group(['prefix' => 'ServiceFees', 'as' => 'servicefee.'], function () {
                Route::get('/', [ServiceFeeController::class, 'index'])->name('index');
                Route::post('FilePrice/{id}', [ServiceFeeController::class, 'updateFilePrice']);
                Route::post('MiPrice/{id}', [ServiceFeeController::class, 'updateMiPrice']);
                Route::post('GetFilePrice', [ServiceFeeController::class, 'getFilePrice']);

                Route::get('GetDistancePrice', [DistanceController::class, 'getDistancePrice']);
            });
            Route::resource('AdditionalServices', AdditionalServicesController::class);
            Route::resource('DesiList', DesiListController::class);
            Route::any('DesiListDelete/', [DesiListController::class, 'deleteRow']);
        });

        Route::resource('PriceDraft', PriceDraftsController::class);
        Route::get('GetPriceDrafts', [PriceDraftsController::class, 'GetPriceDrafts']);


    });


});
