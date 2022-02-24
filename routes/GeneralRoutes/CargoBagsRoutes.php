<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\MainCargo\CargoBagsController;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {

    Route::group(['prefix' => 'CargoBags', 'as' => 'cargoBags.'], function () {

        Route::group(['prefix' => 'Agency', 'as' => 'agency'], function () {
            Route::get('/', [CargoBagsController::class, 'agencyIndex'])->name('Index');
            Route::get('/GetCargoBags', [CargoBagsController::class, 'getCargoBags'])->name('GetCargoBags');
            Route::post('/CreateBag', [CargoBagsController::class, 'createBag'])->name('CreateBag');
            Route::post('/GetBagInfo', [CargoBagsController::class, 'getBagInfo'])->name('GetBagInfo');
            Route::post('/DeleteBag', [CargoBagsController::class, 'deleteCargoBag'])->name('DeleteCargoBag');
        });

        Route::post('GetBagGeneralInfo', [CargoBagsController::class, 'getBagGeneralInfo']);
    });

});
