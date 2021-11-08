<?php

use App\Http\Controllers\Backend\TransshipmentCenter\TCController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {
    Route::group(['prefix' => '/TransshipmentCenters', 'middleware' => ['TransshipmentCenterMid'], 'as' => 'TransshipmentCenters.'], (function () {

        Route::get('GetTc', [TCController::class, 'getTC'])->name('getTC');
        Route::post('Info', [TCController::class, 'tcInfo'])->name('info');

        ## new transaction
        Route::get('TCDistricts', [TCController::class, 'tcDistricts'])->name('TcDistricts');
        Route::post('GetDistricts/{direktive?}', [TCController::class, 'getDistricts'])->name('getDistricts');
        Route::post('AddTcDistrict', [TCController::class, 'addTcDistrict'])->name('addTcDistrict');
        Route::get('ListTCDistricts', [TCController::class, 'ListTCDistricts'])->name('ListTCDistricts');
        Route::post('DestroyTCDistricts', [TCController::class, 'destroyTCDistricts']);

        ## canceled
        Route::get('TcAgency', [TCController::class, 'tcAgency'])->name('TcAgency');
        Route::post('GetAgencies/{direktive?}', [TCController::class, 'getAgencies'])->name('getAgencies');
        Route::post('GiveAgency', [TCController::class, 'giveAgency'])->name('giveAgency');
        Route::get('ListTCAgency', [TCController::class, 'listTCAgency'])->name('ListTCAgency');
        Route::post('DestroyTCAgency', [TCController::class, 'destroyTCAgency']);
    }));
    Route::resource('TransshipmentCenters', TCController::class)->middleware('TransshipmentCenterMid');
});
