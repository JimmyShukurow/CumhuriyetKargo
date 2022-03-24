<?php

use App\Http\Controllers\Backend\Marketing\SenderCurrentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Backend\User\UserGM\UserController;
use App\Http\Controllers\Backend\Personel\PersonelController;
use App\Http\Controllers\Backend\User\UserAgency\UserAgencyController;
use App\Http\Controllers\Backend\User\UserTC\UserTCController;
use App\Http\Controllers\CKG_Mobile\DefaultController;
use App\Http\Controllers\CKG_Mobile\DebitController;
use App\Http\Controllers\CKG_Mobile\CargoController;
use App\Http\Controllers\CKG_Mobile\ExpeditionCargoMobileController;
use App\Http\Controllers\CKG_Barcoder\CKGBarcoderController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

# GM User Logs
Route::get('Users/GetUserLogs', [UserController::class, 'getUserLogs'])->name('user.gm.GetLogs');

# Agency User Logs
Route::get('AgencyUsers/GetUserLogs', [UserAgencyController::class, 'getUserLogs'])->name('AgencyUsers.GetLogs');

# TC User Logs
Route::get('TCUsers/GetUserLogs', [UserTCController::class, 'getUserLogs'])->name('TCUsers.GetLogs');

# GM All Users
Route::get('Users/GetAllUsers', [UserController::class, 'getAllUsers'])->name('user.gm.getAllUsers');

# Perosonel Last Logs
Route::get('Personel/GetLastLogs', [PersonelController::class, 'getUserLastLogs'])->name('personel.GetLastLogs');

## Ajax ==> Email There Is Check <== Ajax ##
Route::post('Users/CheckEmail', [UserController::class, 'checkEmail']);

Route::post('login', [DefaultController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    Route::post('/logout', [DefaultController::class, 'logout']);
    Route::get('/user', [DefaultController::class, 'user']);
    Route::get('/GetDefaultData/{val?}', [DefaultController::class, 'getDefaultData']);
    Route::post('DefaultTransaction/{val?}', [DefaultController::class, 'defaultTransaction']);

    Route::any('DebitTransaction/{val?}', [DebitController::class, 'debitTransaction']);
    Route::post('CargoTransaction/{val?}', [CargoController::class, 'cargoTransaction']);


    Route::post('CaroBagTransactions/{val?}', [CargoController::class, 'caroBagTransactions']);

    Route::group(['prefix' => 'Expedition'], function () {
        Route::post('LoadCargo', [ExpeditionCargoMobileController::class, 'loadCargo']);
        Route::post('Read', [ExpeditionCargoMobileController::class, 'readExpedition']);
        Route::post('UnloadCargo', [ExpeditionCargoMobileController::class, 'unloadCargo']);
        Route::delete('DeleteCargo', [ExpeditionCargoMobileController::class, 'deleteCargo']);
    });

});

Route::group(['prefix' => 'CKG-Barcoder'], function () {

    Route::get('Transactions/{transaction}', [CKGBarcoderController::class, 'transactions']);

});



//Route::middleware('auth:auth')->group(function () {
//
//Route::group(['middleware' => 'ApiAuthenticate'], function () {
//    Route::post('/logout', [DefaultController::class, 'logout']);
//    Route::get('/user', [DefaultController::class, 'user']);
//    Route::get('/GetDefaultData/{val?}', [DefaultController::class, 'getDefaultData']);
//    Route::post('DefaultTransaction/{val?}', [DefaultController::class, 'defaultTransaction']);
//
//    Route::any('DebitTransaction/{val?}', [DebitController::class, 'debitTransaction']);
//    Route::post('CargoTransaction/{val?}', [CargoController::class, 'cargoTransaction']);
//    });
//});




