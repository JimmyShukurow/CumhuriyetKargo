<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Tutorials\AdminTutorialController;
use App\Http\Controllers\Backend\Tutorials\TutorialController;
use App\Actions\CKGSis\Tutorial\Ajax\FilterTutorialsAction;
use App\Actions\CKGSis\Tutorial\Ajax\GetAllTutorialsAction;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {
    Route::group(['prefix' => 'Tutorials'], function () {

        Route::group(['prefix' => 'Admin'], function () {
            Route::resource('tutorial', AdminTutorialController::class);
            Route::get('/Ajax/GetAllTutorials', GetAllTutorialsAction::class);
        });

        Route::get('tutorial', [TutorialController::class, 'index'])->name('user_all_tutorials');
        Route::post('/Ajax/GetAllTutorials', FilterTutorialsAction::class);


    });

});


