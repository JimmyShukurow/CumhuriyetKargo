<?php

use App\Http\Controllers\Backend\ServiceFee\ServiceFeeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\ServiceFee\AdditionalServicesController;

Route::group(
    ['middleware' => ['CheckAuth', 'CheckStatus']],
    function () {
        # ==> Services Fee Transaction START
        Route::group(['middleware' => 'GeneralServicesFeeMid'], function () {
            Route::group(['prefix' => 'ServiceFees', 'as' => 'servicefee.'], function () {
                Route::get('/', [ServiceFeeController::class, 'index'])->name('index');
                Route::post('FilePrice/{id}', [ServiceFeeController::class, 'updateFilePrice']);
                Route::post('MiPrice/{id}', [ServiceFeeController::class, 'updateMiPrice']);
                Route::post('GetFilePrice', [ServiceFeeController::class, 'getFilePrice']);
            });
            Route::resource('AdditionalServices', AdditionalServicesController::class);
            Route::resource('DesiList', DesiListController::class);
        });
        # ==> Services Fee Transaction END
    }
);
