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

Route::get('/', [DefaultController::class, 'login'])->name('Login')->middleware('CheckLogin');
Route::post('/', [DefaultController::class, 'authenticate'])->name('admin.Authenticate')
    ->middleware('throttle:10,1');

Route::get('/ForgetPassword/{TimeOut?}', [DefaultController::class, 'forgetPassword'])->name('forgetPassword');
Route::post('/ConfirmEmail', [DefaultController::class, 'confirmEmail'])->name('confirmEmail')
    ->middleware('throttle:20,1');

Route::get('/RecoverPassword/{UserID}', [DefaultController::class, 'recoverPassword'])->name('recoverPassword');

Route::post('/ConfirmSecurityCode', [DefaultController::class, 'confirmSecurityCode'])
    ->name('confirmSecurityCode')->middleware('throttle:10,1');

Route::get('Logout', [DefaultController::class, 'logout'])->name('admin.Logout');
Route::get('CloseTheVirtualLogin/{id}', [DefaultController::class, 'closeTheVirtualLogin'])->name('closeTheVirtualLogin');

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {

    #GM ALL currents
    Route::get('Customers/GetAllCustomers', [SenderCurrentController::class, 'getAllCustomers'])->name('customer.gm.getAllCustomers');
    Route::get('Customers', [SenderCurrentController::class, 'customersIndex'])->name('customers.index');
    Route::get('Customers/GetAllCustomers', [SenderCurrentController::class, 'getAllCustomers'])->name('customer.gm.getAllCustomers');


    Route::resource('VariousCars', VariousController::class)
        ->middleware('TransferCarsMid');

    Route::resource('TransferCars', TransferCarsController::class)
        ->middleware('TransferCarsMid');

    Route::group(['middleware' => 'TransferCarsMid'], function () {
        Route::get('AllTransferCarsData', [TransferCarsController::class, 'allData'])->name('transfer.car.all');
        Route::post('GetTransferCar', [TransferCarsController::class, 'getTransferCar'])->name('getTransferCars');
    });

    Route::get('VariousCarsGetCars', [VariousController::class, 'getCars'])->name('VariousCars.getCars');

    Route::group(['prefix' => 'OfficialReport', 'as' => 'OfficialReport.'], function () {
        Route::get('HTF', [HtfController::class, 'createHTF'])->name('createHTF');
    });



    Route::group(['prefix' => 'Reports', 'as' => 'reports.'], function () {
        Route::get('/IncomingCargoes', [ReportController::class, 'incomingCargoes'])->name('incomingCargoes');
        Route::get('/OutgoingCargoes', [ReportController::class, 'outgoingCargoes'])->name('outcomingCargoes');
        Route::get('/GetIncomingCargoes', [ReportController::class, 'getIncomingCargoes']);
    });

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

    # ==> Services Fee Transaction START
    Route::group(['middleware' => 'GeneralServicesFeeMid'], function () {
        Route::group(['prefix' => 'ServiceFees', 'as' => 'servicefee.'], function () {
            Route::get('/', [ServiceFeeController::class, 'index'])->name('index');
            Route::post('FilePrice/{id}', [ServiceFeeController::class, 'updateFilePrice']);
            Route::post('MiPrice/{id}', [ServiceFeeController::class, 'updateMiPrice']);
            Route::post('GetFilePrice', [ServiceFeeController::class, 'getFilePrice']);
        });
        Route::resource('AdditionalServices', AdditionalServicesController::class);
        Route::resource('DesiList', DesiListController::class);
    });
    # ==> Services Fee Transaction END


    # ==> Sender Currents Transactions START
    Route::group(['middleware' => 'SenderCurrentsMid'], function () {
        Route::group(['prefix' => 'SenderCurrents', 'as' => 'senderCurrents.'], function () {
            Route::post('AjaxTransaction/{transaction}', [SenderCurrentController::class, 'ajaxTransaction']);
            Route::get('GetCurrents', [SenderCurrentController::class, 'getCurrents'])->name('getCurrents');
            Route::get('CurrentContract/{CurrentCode}', [SenderCurrentController::class, 'printCurrentContract']);
        });
        Route::resource('SenderCurrents', SenderCurrentController::class);
    });
    # ==> Sender Currents Transactions END



    Route::group(['prefix' => '/Theme', 'middleware' => ['ThemeMid']], function () {
        Route::get('/', [ThemeController::class, 'index'])->name('theme.Index');
    });

    # Agency Users Transaction
    Route::group(['prefix' => '/AgencyUsers', 'middleware' => ['AgencyUserMid'], 'as' => 'AgencyUsers.'], (function () {
        Route::get('UserLogs', [UserAgencyController::class, 'userLogs'])->name('userLogs');
        Route::get('PasswordReset/{id}', [UserAgencyController::class, 'agencyPasswordReset'])->name('passwordReset');
    }));

    Route::resource('AgencyUsers', UserAgencyController::class)
        ->middleware('AgencyUserMid');

    # Transshipment Center Users Transactions
    Route::group(['prefix' => '/TCUsers', 'middleware' => ['TCUserMid'], 'as' => 'TCUsers.'], (function () {
        Route::get('UserLogs', [UserTCController::class, 'userLogs'])->name('userLogs');
        Route::get('PasswordReset/{id}', [UserTCController::class, 'tcPasswordReset'])->name('passwordReset');
    }));

    Route::resource('TCUsers', UserTCController::class)
        ->middleware('TCUserMid');

    Route::prefix('Ajax')->group(function () {
        Route::name('ajax.')->group(function () {
            Route::post('CityToDistrict', [AjaxController::class, 'cityToDistrict'])->name('city.to.district');
            Route::post('DistrictToNeighborhood', [AjaxController::class, 'districtToNeighborhood'])->name('district.to.neighborhood');
            Route::get('/SystemUpdatesShow/{id}', [ModuleController::class, 'systemUpdateShow']);
            Route::post('GetAgency', [AjaxController::class, 'getAgency']);
        });
    });

    # Main Routes
    Route::get('/SystemUpdates', [ModuleController::class, 'systemUpdateView']);
});

Route::get('notyet', [DefaultController::class, 'notyet'])->name('not.yet');

//Route::get('not.yet', [DefaultController::class, 'notyet'])->name('not.yet');
//Route::get('nxot.yet', [DefaultController::class, 'notyet'])->name('mainCargo.search');
//Route::get('nyot.yet', [DefaultController::class, 'notyet'])->name('TransferCars.index');
