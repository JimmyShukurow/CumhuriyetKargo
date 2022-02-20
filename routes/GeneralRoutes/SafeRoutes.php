<?php

use App\Http\Controllers\Backend\Safe\AgencySafeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Safe\GeneralSafeController;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {

    Route::group(['prefix' => 'Safe', 'as' => 'safe.'], function () {

        Route::group(['middleware' => ['AgencySafeMid', 'throttle:30,1'], 'prefix' => 'Agency', 'as' => 'agency.'], function () {
            Route::get('/', [AgencySafeController::class, 'index'])->name('index');
            Route::get('AjaxTransactions/{val?}', [AgencySafeController::class, 'ajaxTransactions']);
            Route::get('CreatePaymentApp', [AgencySafeController::class, 'createPaymentApp'])->name('createPaymentApp');
            Route::post('InsertPaymentApp', [AgencySafeController::class, 'insertPaymentApp'])->name('insertPaymentApp');
        });

        Route::group(['middleware' => 'GeneralSafeMid', 'prefix' => 'General', 'as' => 'general.'], function () {
            Route::get('/', [GeneralSafeController::class, 'index'])->name('index');
            Route::get('AjaxTransactions/{val?}', [GeneralSafeController::class, 'ajaxTransactions']);
        });

    });

});
