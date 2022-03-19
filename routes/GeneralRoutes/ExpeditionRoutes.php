<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\OfficialReports\OfficialReportController;
use App\Http\Controllers\Backend\Expedition\ExpeditionController;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {

    Route::group(['prefix' => 'Expedition', 'as' => 'expedition.'], function () {
        Route::get('Create', [ExpeditionController::class, 'create'])->name('create');
        Route::post('Store', [ExpeditionController::class, 'store']);
        Route::get('Details/{id?}', [ExpeditionController::class, 'Show'])->name('expedition-details');
        Route::post('Finish/{id?}', [ExpeditionController::class, 'finish'])->name('expedition-details');
        Route::delete('Delete', [ExpeditionController::class, 'delete']);

        Route::get('OutGoing', [ExpeditionController::class, 'outGoing'])->name('outGoing');
        Route::any('AjaxTransactions/{val}', [ExpeditionController::class, 'ajaxTransactions']);
    });


});
