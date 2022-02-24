<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Marketing\SenderCurrentController;


Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {

    Route::group(['middleware' => 'SenderCurrentsMid'], function () {
        Route::group(['prefix' => 'SenderCurrents', 'as' => 'senderCurrents.'], function () {
            Route::post('AjaxTransaction/{transaction}', [SenderCurrentController::class, 'ajaxTransaction']);
            Route::get('GetCurrents', [SenderCurrentController::class, 'getCurrents'])->name('getCurrents');
            Route::get('CurrentContract/{CurrentCode}', [SenderCurrentController::class, 'printCurrentContract']);
        });
        Route::resource('SenderCurrents', SenderCurrentController::class);
    });

});
