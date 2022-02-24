<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\User\UserTC\UserTCController;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {

# Transshipment Center Users Transactions
    Route::group(['prefix' => '/TCUsers', 'middleware' => ['TCUserMid'], 'as' => 'TCUsers.'], (function () {
        Route::get('UserLogs', [UserTCController::class, 'userLogs'])->name('userLogs');
        Route::get('PasswordReset/{id}', [UserTCController::class, 'tcPasswordReset'])->name('passwordReset');
    }));

    Route::resource('TCUsers', UserTCController::class)
        ->middleware('TCUserMid');
});
