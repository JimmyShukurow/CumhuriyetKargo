<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Dashboard\DashboardController;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {

    Route::group(['prefix' => 'Dashboard', 'as' => 'dashboard.'], function () {

        Route::group(['prefix' => 'GM', 'middleware' => ['DashboardGmMid']], function () {
            Route::get('/', [DashboardController::class, 'gmDashboard'])->name('gmDashboard');
            Route::any('AjaxTransactions/{val}', [DashboardController::class, 'gmAjaxTransactions']);
        });

    });
});
