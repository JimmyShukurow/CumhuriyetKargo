<?php

use App\Http\Controllers\Backend\Personel\PersonelController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {
    Route::prefix('/Personel')->group(function () {
        Route::name('personel.')->group(function () {
            Route::get('LastLogs', [PersonelController::class, 'lastLogs'])->name('LastLogs');
            Route::get('AccountSettings', [PersonelController::class, 'accountSettings'])->name('AccountSettings');
            Route::post('ChangePassword', [PersonelController::class, 'changePassword'])->name('ChangePassword');
            Route::post('MarkAsRead', [PersonelController::class, 'markAsRead'])->name('markAsRead');
            Route::get('NotificationAndAnnouncements/{Tab?}', [PersonelController::class, 'notificationAndAnnouncements'])->name('notificationAndAnnouncements');
        });
    });
    Route::post('SearchModule', [PersonelController::class, 'searchModule']);
});
