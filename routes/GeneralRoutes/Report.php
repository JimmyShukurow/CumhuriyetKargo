<?php

use App\Http\Controllers\Backend\Reports\ReportController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {
    Route::group(['prefix' => 'Reports', 'as' => 'reports.'], function () {
        Route::get('/IncomingCargoes', [ReportController::class, 'incomingCargoes'])->name('incomingCargoes');
        Route::get('/OutgoingCargoes', [ReportController::class, 'outgoingCargoes'])->name('outcomingCargoes');
        Route::get('/GetIncomingCargoes', [ReportController::class, 'getIncomingCargoes']);
    });
});
