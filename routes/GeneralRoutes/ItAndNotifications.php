<?php

use App\Http\Controllers\Backend\ItAndNotifications\CargoCancellationController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {
    Route::group(['prefix' => 'ItAndNotification'], function () {
        Route::group(['as' => 'cargoCancel.', 'middleware' => 'ItAndNotificationMidX'], function () {
            Route::get('CargoCancellations', [CargoCancellationController::class, 'index'])->name('index');
            Route::get('/GetCancellations', [CargoCancellationController::class, 'getCancellations'])->name('getCancellations');
            Route::post('SetCargoCancellationApplicationResult', [CargoCancellationController::class, 'setCargoCancellationApplicationResult'])
                ->name('setCargoCancellationApplicationResult');
            Route::post('PageRowCount', [CargoCancellationController::class, 'pageRowCount'])
                ->name('pageRowCount');
            Route::post('BackupCargo', [CargoCancellationController::class, 'backupCargo'])
                ->name('backupCargo');
        });
    });
});
