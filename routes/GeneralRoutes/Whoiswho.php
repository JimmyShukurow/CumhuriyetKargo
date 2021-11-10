<?php

use App\Http\Controllers\Backend\WhoIs\WhoIsController;
use Illuminate\Support\Facades\Route;


Route::group(
    ['middleware' => ['CheckAuth', 'CheckStatus']],
    function () {
        Route::group(['prefix' => 'WhoIsWho', 'as' => 'whois.'], function () {
            Route::get('', [WhoIsController::class, 'index'])->name('index');
            Route::get('TransshipmentCenters', [WhoIsController::class, 'transshipmentCenters'])->name('tc');

            // Route::get('GetTransshipmentCenters', [WhoIsController::class, 'getTransshipmentCentersData'])->name('tcdata');
            Route::get('Agencies', [WhoIsController::class, 'index_agencies'])->name('agencies');
            Route::get('GetAgencies', [WhoIsController::class, 'getAgencies'])->name('GetAgencies');

            Route::get('GetTransshipmentCenters', [WhoIsController::class, 'getTransshipmentCenters'])->name('getTransshipmentCenters');
            Route::post('GetTransshipmentCentersData', [WhoIsController::class, 'getTransshipmentCentersData'])->name('Transshipment');

            Route::post('GetAgencyInfo', [WhoIsController::class, 'agencyInfo'])->name('agencyInfo');

            Route::get('GetUsers', [WhoIsController::class, 'getUsers'])->name('getUsers');
            Route::post('GetUserInfo', [WhoIsController::class, 'userInfo']);
        });
    }
);

Route::get('Deneme', function () {
    return crypteTrackingNo("342120346789696 1");
    //return decryptTrackingNo('%OSGUDYGUJ%OSX$ZOFXFX');
});
