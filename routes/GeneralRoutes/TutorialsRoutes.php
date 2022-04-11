<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Tutorials\TutorialController;


Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {
    Route::group(['prefix' => 'Tutorials'], function () {
        Route::resource('tutorial',TutorialController::class);
    });
});
