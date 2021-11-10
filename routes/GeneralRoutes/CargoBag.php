<?php

use App\Http\Controllers\Backend\MainCargo\CargoBagsController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'CargoBags', 'as' => 'cargoBags.'], function () {

    Route::group(['prefix' => 'Agency', 'as' => 'agency'], function () {
        Route::get('/', [CargoBagsController::class, 'agencyIndex'])->name('Index');
        Route::get('/GetCargoBags', [CargoBagsController::class, 'getCargoBags'])->name('GetCargoBags');
        Route::post('/CreateBag', [CargoBagsController::class, 'createBag'])->name('CreateBag');
        Route::post('/GetBagInfo', [CargoBagsController::class, 'getBagInfo'])->name('GetBagInfo');
    });
});