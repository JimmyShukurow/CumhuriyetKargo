<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\User\UserAgency\UserAgencyController;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {

    Route::group(['prefix' => '/AgencyUsers', 'middleware' => ['AgencyUserMid'], 'as' => 'AgencyUsers.'], (function () {
        Route::get('UserLogs', [UserAgencyController::class, 'userLogs'])->name('userLogs');
        Route::get('PasswordReset/{id}', [UserAgencyController::class, 'agencyPasswordReset'])->name('passwordReset');
    }));

    Route::resource('AgencyUsers', UserAgencyController::class)
        ->middleware('AgencyUserMid');
});
