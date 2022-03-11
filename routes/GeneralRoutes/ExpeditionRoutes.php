<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\OfficialReports\OfficialReportController;
use App\Http\Controllers\Backend\Expedition\ExpeditionController;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {
    Route::group(['prefix' => 'OfficialReport', 'as' => 'OfficialReport.'], function () {

        Route::get('/', [OfficialReportController::class, 'index'])->name('index');
        Route::get('GetOfficialReports', [OfficialReportController::class, 'getOfficialReports']);

        Route::get('HTF', [OfficialReportController::class, 'createHTF'])->name('createHTF');
        Route::post('CreateHTF', [OfficialReportController::class, 'insertHTF']);

        Route::get('UTF', [OfficialReportController::class, 'createUTF'])->name('createUTF');
        Route::post('CreateUTF', [OfficialReportController::class, 'insertUTF']);

        Route::get('OutgoingReports/{requestID?}', [OfficialReportController::class, 'outgoingReports'])->name('outgoingReports');
        Route::get('GetOutGoingReports', [OfficialReportController::class, 'getOutGoingReports']);
        Route::post('MakeAnOpinion', [OfficialReportController::class, 'makeAnOpinion']);


        Route::get('IncomingReports/{requestID?}', [OfficialReportController::class, 'incomingReports'])->name('incomingReports');
        Route::get('GetIncomingReports', [OfficialReportController::class, 'getIncomingReports']);
        Route::post('MakeAnObjection', [OfficialReportController::class, 'makeAnObjection']);


        Route::group(['middleware' => ['ManageReportMid']], function () {
            Route::get('ManageReports', [OfficialReportController::class, 'manageReport'])->name('manageReport');
            Route::get('GetManageReports', [OfficialReportController::class, 'getManageReports']);
            Route::post('EnterConfirmResult', [OfficialReportController::class, 'enterConfirmResult']);
        });

        Route::post('GetReportInfo', [OfficialReportController::class, 'getReportInfo']);
    });

    Route::group(['prefix' => 'Expedition', 'as' => 'expedition.'], function () {

        Route::get('Create', [ExpeditionController::class, 'create'])->name('create');


    });


});
