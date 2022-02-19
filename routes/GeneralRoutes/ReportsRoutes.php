<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Reports\ReportController;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {

    Route::group(['prefix' => 'Reports', 'as' => 'reports.'], function () {
        Route::get('/IncomingCargoes', [ReportController::class, 'incomingCargoes'])->name('incomingCargoes');
        Route::get('/OutgoingCargoes', [ReportController::class, 'outgoingCargoes'])->name('outcomingCargoes');
        Route::get('/GetIncomingCargoes', [ReportController::class, 'getIncomingCargoes']);
        Route::get('/GetOutGoingCargoes', [ReportController::class, 'getOutGoingCargoes']);
    });
});
