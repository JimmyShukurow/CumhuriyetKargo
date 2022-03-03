<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Dashboard\DashboardController;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {

    Route::group(['prefix' => 'Dashboard', 'as' => 'dashboard.'], function (){

        Route::get('GM', [DashboardController::class, 'gmDashboard'])->name('gmDashboard');

    });
});
