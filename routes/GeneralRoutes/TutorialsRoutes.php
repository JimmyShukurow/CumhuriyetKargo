<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Tutorials\TutorialController;
use App\Actions\CKGSis\Tutorial\Ajax\GetAllTutorialsAction;


Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {
    Route::group(['prefix' => 'Tutorials'], function () {
        Route::resource('tutorial',TutorialController::class);
        Route::get('/Ajax/GetAllTutorials', GetAllTutorialsAction::class);
    });
});


