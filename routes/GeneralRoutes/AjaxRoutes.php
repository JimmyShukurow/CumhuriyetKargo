<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AjaxController;
use App\Http\Controllers\Backend\Module\ModuleController;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {
    Route::prefix('Ajax')->group(function () {
        Route::name('ajax.')->group(function () {
            Route::post('CityToDistrict', [AjaxController::class, 'cityToDistrict'])->name('city.to.district');
            Route::post('DistrictToNeighborhood', [AjaxController::class, 'districtToNeighborhood'])->name('district.to.neighborhood');
            Route::get('/SystemUpdatesShow/{id}', [ModuleController::class, 'systemUpdateShow']);
            Route::post('GetAgency', [AjaxController::class, 'getAgency']);
            Route::post('/GetCarInfo', [AjaxController::class, 'getCarInfo']);
            Route::get('AllAgencies', [AjaxController::class, 'getAllAgencies']);
            Route::get('AllTransshipmentCenters', [AjaxController::class, 'getAllTransshipmentCenters']);
        });
    });
});
