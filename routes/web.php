<?php

use App\Http\Controllers\Backend\DefaultController;
use App\Http\Controllers\Backend\RegionalDirectorate\RDController;
use App\Http\Controllers\TransferCarsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Agency\AgencyController;
use App\Http\Controllers\Backend\User\UserGM\UserController;
use App\Http\Controllers\Backend\Module\ModuleController;
use App\Http\Controllers\Backend\AjaxController;
use App\Http\Controllers\Backend\Module\SortingController;
use App\Http\Controllers\Backend\TransshipmentCenter\TCController;
use App\Http\Controllers\Backend\Theme\ThemeController;
use App\Http\Controllers\Backend\Personel\PersonelController;
use App\Http\Controllers\Backend\SystemSupport\SystemSupportController;
use App\Http\Controllers\Backend\Department\DepartmentController;
use App\Http\Controllers\Backend\User\UserAgency\UserAgencyController;
use App\Http\Controllers\Backend\User\UserTC\UserTCController;
use App\Http\Controllers\Backend\SystemSupport\AdminSystemSupportController;
use App\Http\Controllers\Backend\ServiceFee\ServiceFeeController;
use App\Http\Controllers\Backend\ServiceFee\AdditionalServicesController;
use App\Http\Controllers\Backend\ServiceFee\DesiListController;
use App\Http\Controllers\Backend\MainCargo\MainCargoController;
use App\Http\Controllers\Backend\Marketing\SenderCurrentController;
use App\Http\Controllers\Backend\Operation\LocalLocationController;
use App\Http\Controllers\Backend\Operation\VariousController;
use App\Http\Controllers\Backend\WhoIs\WhoIsController;
use App\Http\Controllers\Backend\ItAndNotifications\CargoCancellationController;
use App\Http\Controllers\Backend\OfficialReports\HtfController;
use App\Http\Controllers\Backend\MainCargo\CargoBagsController;
use App\Http\Controllers\Backend\Reports\ReportController;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Backend\OfficialReports\OfficialReportController;
use App\Http\Controllers\Safe\AgencySafeController;
use App\Http\Controllers\Backend\Region\RegionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
|
|
| # SysCumhuriyetKargo Project Start Date: 29th of March 2021 Monday 16:27
*/

Route::get('/', [DefaultController::class, 'login'])
    ->name('Login')
    ->middleware('CheckLogin');

Route::post('/', [DefaultController::class, 'authenticate'])
    ->name('admin.Authenticate')
    ->middleware('throttle:10,1');

Route::get('login', function () {
    return redirect(\route('Login'))
        ->with('error', 'Başka bir cihazdan hesabınıza giriş yapıldı!');
})->name('login');

Route::get('/ForgetPassword/{TimeOut?}', [DefaultController::class, 'forgetPassword'])
    ->name('forgetPassword');

Route::post('/ConfirmEmail', [DefaultController::class, 'confirmEmail'])
    ->name('confirmEmail')
    ->middleware('throttle:20,1');

Route::get('/RecoverPassword/{UserID}', [DefaultController::class, 'recoverPassword'])
    ->name('recoverPassword');

Route::post('/ConfirmSecurityCode', [DefaultController::class, 'confirmSecurityCode'])
    ->name('confirmSecurityCode')
    ->middleware('throttle:10,1');

Route::post('/ConfirmLoginSecurityCode', [DefaultController::class, 'confirmLoginSecurityCode'])
    ->middleware('throttle:10,1');

Route::get('Logout', [DefaultController::class, 'logout'])
    ->name('admin.Logout');
Route::get('CloseTheVirtualLogin/{id}', [DefaultController::class, 'closeTheVirtualLogin'])
    ->name('closeTheVirtualLogin');

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {

    Route::group(['prefix' => '/Theme', 'middleware' => ['ThemeMid']], function () {
        Route::get('/', [ThemeController::class, 'index'])->name('theme.Index');
    });

    # Main Routes
    Route::get('/SystemUpdates', [ModuleController::class, 'systemUpdateView']);
});

Route::get('notyet', [DefaultController::class, 'notyet'])->name('not.yet');
Route::get('notyeXt', [DefaultController::class, 'notyet'])->name('CemIndex');

