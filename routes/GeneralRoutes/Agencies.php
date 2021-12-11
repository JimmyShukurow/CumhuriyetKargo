<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Agency\AgencyController;
Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {
    Route::group(['prefix' => '/Agencies', 'middleware' => ['AgenciesMid'], 'as' => 'agency.'], function () {
        Route::get('', [AgencyController::class, 'index'])->name('Index');
        Route::get('GetAgencies', [AgencyController::class, 'getAgencies'])->name('getAgencies');
        Route::get('AddAgency', [AgencyController::class, 'addAgency'])->name('AddAgency');
        Route::post('AddAgency', [AgencyController::class, 'insertAgency'])->name('InsertAgency');
        Route::get('EditAgency/{id}', [AgencyController::class, 'editAgency'])->name('EditAgency')->where(['id' => '[0-9]+']);
        Route::post('EditAgency/{id}', [AgencyController::class, 'updateAgency'])->name('UpdateAgency')->where(['id' => '[0-9]+']);
        Route::post('DestroyAgency', [AgencyController::class, 'destroyAgency'])->name('DestroyAgency');
        ### ==> Ajax Info <== ###
        Route::post('Info', [AgencyController::class, 'agencyInfo'])->name('Info');
        Route::post('ChangeStatus', [AgencyController::class, 'changeStatus'])->name('ChangeStatus');
    });
});
