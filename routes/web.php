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

Route::get('/', [DefaultController::class, 'login'])->name('Login')->middleware('CheckLogin');
Route::post('/', [DefaultController::class, 'authenticate'])->name('admin.Authenticate')
    ->middleware('throttle:10,1');

Route::get('login', function () {
    return redirect(\route('Login'))->with('error', 'Başka bir cihazdan hesabınıza giriş yapıldı!');
})->name('login');

Route::get('/ForgetPassword/{TimeOut?}', [DefaultController::class, 'forgetPassword'])->name('forgetPassword');
Route::post('/ConfirmEmail', [DefaultController::class, 'confirmEmail'])->name('confirmEmail')
    ->middleware('throttle:20,1');

Route::get('/RecoverPassword/{UserID}', [DefaultController::class, 'recoverPassword'])->name('recoverPassword');

Route::post('/ConfirmSecurityCode', [DefaultController::class, 'confirmSecurityCode'])
    ->name('confirmSecurityCode')->middleware('throttle:10,1');

Route::post('/ConfirmLoginSecurityCode', [DefaultController::class, 'confirmLoginSecurityCode'])
    ->middleware('throttle:10,1');

Route::get('Logout', [DefaultController::class, 'logout'])->name('admin.Logout');
Route::get('CloseTheVirtualLogin/{id}', [DefaultController::class, 'closeTheVirtualLogin'])->name('closeTheVirtualLogin');

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {


    Route::group(['prefix' => 'Region', 'as' => 'region.'], function (){
        Route::get('RelationPlaces', [RegionController::class, 'relationPlaces'])->name('relationPlaces');
    });


    Route::prefix('Customers')->group(function () {
        #GM ALL currents
        Route::get('GetAllCustomers', [SenderCurrentController::class, 'getAllCustomers'])->name('customer.gm.getAllCustomers');
        Route::get('/', [SenderCurrentController::class, 'customersIndex'])->name('customers.index');
        Route::get('GetAllCustomers', [SenderCurrentController::class, 'getAllCustomers'])->name('customer.gm.getAllCustomers');
        Route::post('GetCustomerInfo', [SenderCurrentController::class, 'getCustomerById']);
        Route::delete('/Delete/{id}', [SenderCurrentController::class, 'deleteCustomer']);
    });


    Route::group(['prefix' => 'Safe', 'as' => 'safe.'], function () {

        Route::group(['prefix' => 'Agency', 'as' => 'agency.'], function () {
            Route::get('/', [AgencySafeController::class, 'index'])->name('index');
        });

    });


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


    Route::group(['prefix' => 'Reports', 'as' => 'reports.'], function () {
        Route::get('/IncomingCargoes', [ReportController::class, 'incomingCargoes'])->name('incomingCargoes');
        Route::get('/OutgoingCargoes', [ReportController::class, 'outgoingCargoes'])->name('outcomingCargoes');
        Route::get('/GetIncomingCargoes', [ReportController::class, 'getIncomingCargoes']);
        Route::get('/GetOutGoingCargoes', [ReportController::class, 'getOutGoingCargoes']);
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
            Route::post('/GetCarInfo', [AjaxController::class, 'getCarInfo']);
        });
    });

    # Main Routes
    Route::get('/SystemUpdates', [ModuleController::class, 'systemUpdateView']);
});

Route::get('notyet', [DefaultController::class, 'notyet'])->name('not.yet');
Route::get('notyeXt', [DefaultController::class, 'notyet'])->name('CemIndex');

//Route::get('not.yet', [DefaultController::class, 'notyet'])->name('not.yet');
//Route::get('nxot.yet', [DefaultController::class, 'notyet'])->name('mainCargo.search');
//Route::get('nyot.yet', [DefaultController::class, 'notyet'])->name('TransferCars.index');
