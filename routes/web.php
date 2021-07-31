<?php

use App\Http\Controllers\Backend\DefaultController;
use App\Http\Controllers\Backend\RegionalDirectorate\RDController;
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
    ->middleware('throttle:10,1');;

Route::get('/ForgetPassword/{TimeOut?}', [DefaultController::class, 'forgetPassword'])->name('forgetPassword');
Route::post('/ConfirmEmail', [DefaultController::class, 'confirmEmail'])->name('confirmEmail')
    ->middleware('throttle:20,1');

Route::get('/RecoverPassword/{UserID}', [DefaultController::class, 'recoverPassword'])->name('recoverPassword');

Route::post('/ConfirmSecurityCode', [DefaultController::class, 'confirmSecurityCode'])
    ->name('confirmSecurityCode')->middleware('throttle:10,1');

Route::get('Logout', [DefaultController::class, 'logout'])->name('admin.Logout');
Route::get('CloseTheVirtualLogin/{id}', [DefaultController::class, 'closeTheVirtualLogin'])->name('closeTheVirtualLogin');

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {

    # Main Routes
    Route::get('/SystemUpdates', [ModuleController::class, 'systemUpdateView']);

    # ==> Main Cargo Transaction
    Route::group(['prefix' => 'MainCargo', 'as' => 'mainCargo.'], function () {
        Route::get('NewCargo', [MainCargoController::class, 'newCargo'])->name('newCargo');
        Route::get('', [MainCargoController::class, 'index'])->name('index');
        Route::get('GetCargoes', [MainCargoController::class, 'getMainCargoes'])->name('getCargoes');
        Route::post('AjaxTransactions/{transaction}', [MainCargoController::class, 'ajaxTransacrtions']);
    });


    Route::group(['prefix' => 'Operation'], function () {
//        Route::get('LocalLocation', )
        Route::resource('LocalLocation', LocalLocationController::class);
        Route::get('GetLocations', [LocalLocationController::class, 'getLocation'])->name('operation.getLocation');


    });

    # ==> Services Fee Transaction
    Route::group(['prefix' => 'ServiceFees', 'as' => 'servicefee.'], function () {
        Route::get('/', [ServiceFeeController::class, 'index'])->name('index');
        Route::post('FilePrice/{id}', [ServiceFeeController::class, 'updateFilePrice']);
        Route::post('GetFilePrice', [ServiceFeeController::class, 'getFilePrice']);
    });
    Route::resource('AdditionalServices', AdditionalServicesController::class);
    Route::resource('DesiList', DesiListController::class);

    # ==> Sender Currents Transactions
    Route::group(['prefix' => 'SenderCurrents', 'as' => 'senderCurrents.'], function () {
        Route::post('AjaxTransaction/{transaction}', [SenderCurrentController::class, 'ajaxTransaction']);
        Route::get('GetCurrents', [SenderCurrentController::class, 'getCurrents'])->name('getCurrents');
    });
    Route::resource('SenderCurrents', SenderCurrentController::class);

    # Personel Transaction
    Route::prefix('/Personel')->group(function () {
        Route::name('personel.')->group(function () {
            Route::get('LastLogs', [PersonelController::class, 'lastLogs'])->name('LastLogs');
            Route::get('AccountSettings', [PersonelController::class, 'accountSettings'])->name('AccountSettings');
            Route::post('ChangePassowrd', [PersonelController::class, 'changePassword'])->name('ChangePassowrd');
            Route::post('MarkAsRead', [PersonelController::class, 'markAsRead'])->name('markAsRead');
            Route::get('NotificationAndAnnouncements/{Tab?}', [PersonelController::class, 'notificationAndAnnouncements'])->name('notificationAndAnnouncements');
        });
    });

    Route::group(['prefix' => 'SystemSupport', 'as' => 'systemSupport.'], function () {
        Route::get('NewTicket', [SystemSupportController::class, 'addTicket'])->name('NewTicket');
        Route::post('SendTicket', [SystemSupportController::class, 'createTicket'])->name('create');
        Route::get('MyTickets', [SystemSupportController::class, 'myTickets'])->name('myTickets');
        Route::post('ReplyTicket', [SystemSupportController::class, 'replyTicket'])->name('replyTicket');
        Route::get('TicketDetails/{TicketID}', [SystemSupportController::class, 'ticketDetails'])->name('TicketDetails');
    });

    Route::group(['prefix' => 'AdminSystemSupport', 'as' => 'admin.systemSupport.', 'middleware' => ['AdminSystemSupportMid']], function () {
        Route::get('/', [AdminSystemSupportController::class, 'index'])->name('index');
        Route::get('/GetTickets', [AdminSystemSupportController::class, 'getTickets'])->name('getTickets');
        Route::post('ReplyTicket', [AdminSystemSupportController::class, 'replyTicket'])->name('replyTicket');
        Route::get('TicketDetails/{TicketID}', [AdminSystemSupportController::class, 'ticketDetails'])->name('TicketDetails');
        Route::post('RedirectTicket', [AdminSystemSupportController::class, 'redirectTicket'])->name('redirectTicket');
        Route::post('UpdateStatusTicket', [AdminSystemSupportController::class, 'updateStatusTicket'])->name('updateStatusTicket');
        Route::post('PageRowCount', [AdminSystemSupportController::class, 'pageRowCount'])->name('pageRowCount');
    });

    Route::group(['prefix' => 'Departments', 'middleware' => ['DepartmentsMid'], 'as' => 'Departments.'], function () {
        Route::get('DepartmentRole', [DepartmentController::class, 'departmentRole'])->name('Roles');
        Route::post('GetRoles/{direktive?}', [DepartmentController::class, 'getRoles'])->name('getRoles');
        Route::post('GiveRole', [DepartmentController::class, 'giveRole'])->name('giveRole');
        Route::get('ListRoleDepartments', [DepartmentController::class, 'listRoleDepartments'])->name('ListRoleDepartments');
        Route::post('DestroyRoleDepartment', [DepartmentController::class, 'destroyRoleDepartment']);
    });
    Route::resource('Departments', DepartmentController::class)
        ->middleware('DepartmentsMid');

    Route::group(['prefix' => '/Theme', 'middleware' => ['ThemeMid']], function () {
        Route::get('/', [ThemeController::class, 'index'])->name('theme.Index');
    });

    Route::group(['prefix' => '/Users', 'middleware' => ['GmUsersMid'], 'as' => 'user.gm.'], (function () {
        ## ==> General Managment Transactions <== ##
        Route::get('/', [UserController::class, 'index'])->name('Index');
        Route::get('AddUser', [UserController::class, 'addUser'])->name('AddUser');
        Route::post('AddUser', [UserController::class, 'insertUser'])->name('Insert');
        Route::get('EditUser/{id}', [UserController::class, 'editUser'])->name('EditUser')->where(['id' => '[0-9]+']);
        Route::post('EditUser/{id}', [UserController::class, 'updateUser'])->name('Update')->where(['id' => '[0-9]+']);
        Route::post('Destroy', [UserController::class, 'destroyUser']);
        Route::post('PasswordReset', [UserController::class, 'userPasswordReset'])->name('passwordReset');
        Route::get('VirtualLogin/{UserID}/{Reason}', [UserController::class, 'virtualLogin'])->name('virtualLogin');
        Route::get('UserLogs', [UserController::class, 'userLogs'])->name('Logs');
        ## Ajax ==> Email There Is Check <== Ajax ##
        Route::post('CheckEmail', [UserController::class, 'checkEmail']);
        ## Ajax ==> Get User Info
        Route::post('GetUserInfo', [UserController::class, 'userInfo']);
        ## Ajax ==> Save Status Info
        Route::post('ChangeStatus', [UserController::class, 'changeStatus']);
    }));

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

    # Transshipment Centers
    Route::group(['prefix' => '/TransshipmentCenters', 'middleware' => ['TransshipmentCenterMid'], 'as' => 'TransshipmentCenters.'], (function () {

        Route::get('GetTc', [TCController::class, 'getTC'])->name('getTC');
        Route::post('Info', [TCController::class, 'tcInfo'])->name('info');

        ## new transaction
        Route::get('TCDistricts', [TCController::class, 'tcDistricts'])->name('TcDistricts');
        Route::post('GetDistricts/{direktive?}', [TCController::class, 'getDistricts'])->name('getDistricts');
        Route::post('AddTcDistrict', [TCController::class, 'addTcDistrict'])->name('addTcDistrict');
        Route::get('ListTCDistricts', [TCController::class, 'ListTCDistricts'])->name('ListTCDistricts');
        Route::post('DestroyTCDistricts', [TCController::class, 'destroyTCDistricts']);

        ## canceled
        Route::get('TcAgency', [TCController::class, 'tcAgency'])->name('TcAgency');
        Route::post('GetAgencies/{direktive?}', [TCController::class, 'getAgencies'])->name('getAgencies');
        Route::post('GiveAgency', [TCController::class, 'giveAgency'])->name('giveAgency');
        Route::get('ListTCAgency', [TCController::class, 'listTCAgency'])->name('ListTCAgency');
        Route::post('DestroyTCAgency', [TCController::class, 'destroyTCAgency']);
    }));
    Route::resource('TransshipmentCenters', TCController::class)->middleware('TransshipmentCenterMid');

    Route::group(['prefix' => '/Agencies', 'middleware' => ['AgenciesMid'], 'as' => 'agency.'], function () {
        Route::get('', [AgencyController::class, 'index'])->name('Index');
        Route::get('GetAgencies', [AgencyController::class, 'getAgencies'])->name('getAgencies');
        Route::get('AddAgency', [AgencyController::class, 'addAgency'])->name('AddAgency');
        Route::post('AddAgency', [AgencyController::class, 'insertAgency'])->name('InsertAgency');
        Route::get('EditAgency/{id}', [AgencyController::class, 'editAgency'])->name('EditAgency')->where(['id' => '[0-9]+']);
        Route::post('EditAgency/{id}', [AgencyController::class, 'updateAgency'])->name('UpdateAgency')->where(['id' => '[0-9]+']);
        Route::post('DestroyAgency', [AgencyController::class, 'destroyAgency'])->name('DestroyAgency');
        ### ==> Ajax Info <== ###
        Route::post('Info', [AgencyController::class, 'agencyInfo'])->name('Info');
    });

    Route::prefix('Ajax')->group(function () {
        Route::name('ajax.')->group(function () {
            Route::post('CityToDistrict', [AjaxController::class, 'cityToDistrict'])->name('city.to.district');
            Route::post('DistrictToNeighborhood', [AjaxController::class, 'districtToNeighborhood'])->name('district.to.neighborhood');
            Route::get('/SystemUpdatesShow/{id}', [ModuleController::class, 'systemUpdateShow']);
        });
    });


    Route::group(['prefix' => '/Module', 'middleware' => ['ModulesMid']], (function () {
        Route::name('module.')->group(function () {
            Route::get('/', [ModuleController::class, 'index'])->name('Index');

            Route::get('/SystemUpdate/', [ModuleController::class, 'systemUpdateIndex'])->name('systemUpdate.Index');
            Route::get('/SystemUpdate/Create', [ModuleController::class, 'systemUpdateCreate'])->name('systemUpdate.Create');
            Route::post('/SystemUpdate/Insert', [ModuleController::class, 'systemUpdateInsert'])->name('systemUpdate.Insert');
            Route::get('/SystemUpdate/Edit/{id}', [ModuleController::class, 'systemUpdateEdit'])->name('systemUpdate.Edit');
            Route::get('/SystemUpdate/Update/{id}', [ModuleController::class, 'systemUpdateUpdate'])->name('systemUpdate.Update');
            Route::get('/SystemUpdate/Delete/{id}', [ModuleController::class, 'systemUpdateDelete'])->name('systemUpdate.Delete');


            ### Role Transactions ###
            Route::get('AddRole', [ModuleController::class, 'addRole'])->name('AddRole');
            Route::post('AddRole', [ModuleController::class, 'insertRole'])->name('InsertRole');
            Route::post('DestroyRole', [ModuleController::class, 'detroyRole'])->name('DetroyRole');
            Route::get('EditRole/{id}', [ModuleController::class, 'editRole'])->name('EditRole');
            Route::post('EditRole/{id}', [ModuleController::class, 'updateRole'])->name('UpdateRole');

            ### ModuleGroups Transactions ###
            Route::get('AddModuleGroup', [ModuleController::class, 'addModuleGroup'])->name('AddModuleGrpup');
            Route::post('AddModuleGroup', [ModuleController::class, 'insertModuleGroup'])->name('ModuleGroup');
            Route::post('DestroyModuleGroup', [ModuleController::class, 'detroyModuleGroup'])->name('DetroyModuleGroup');
            Route::get('EditModuleGroup/{id}', [ModuleController::class, 'editModuleGroup'])->name('EditModuleGroup');
            Route::post('EditModuleGroup/{id}', [ModuleController::class, 'updateModuleGroup'])->name('UpdateModuleGroup');

            ### Modules Transactions ###
            Route::get('AddModule', [ModuleController::class, 'addModule'])->name('AddModule');
            Route::post('AddModule', [ModuleController::class, 'insertModule'])->name('InsertModule');
            Route::post('DestroyModule', [ModuleController::class, 'detroyModule'])->name('DetroyModule');
            Route::get('EditModule/{id}', [ModuleController::class, 'editModule'])->name('EditModule');
            Route::post('EditModule/{id}', [ModuleController::class, 'updateModule'])->name('UpdateModule');

            ### SubModules Transactions ###
            Route::get('AddSubModule', [ModuleController::class, 'addSubModule'])->name('AddSubModule');
            Route::post('AddSubModule', [ModuleController::class, 'insertSubModule'])->name('InsertSubModule');
            Route::post('DestroySubModule', [ModuleController::class, 'detroySubModule'])->name('DetroySubModule');
            Route::get('EditSubModule/{id}', [ModuleController::class, 'editSubModule'])->name('EditSubModule');
            Route::post('EditSubModule/{id}', [ModuleController::class, 'updateSubModule'])->name('UpdateSubModule');

            Route::get('Sorting', [SortingController::class, 'index'])->name('Sorting');
            Route::post('MgSort', [SortingController::class, 'moduleGroupSorting'])->name('mg.Sort');
            Route::post('ModuleSort', [SortingController::class, 'moduleSorting'])->name('module.Sort');
            Route::post('SubModuleSort', [SortingController::class, 'subModuleSorting'])->name('subModule.Sort');
        });

        ### ==> RoleSubModule Permissions Transactions <== ###
        Route::post('GetSubModuleOfRole', [ModuleController::class, 'getSubModuleOfRole']);
        Route::post('GetNonPermissionsOfRole', [ModuleController::class, 'getNonPermissionsOfRole']);
        Route::post('AddSubModuleToRole', [ModuleController::class, 'insertSubModuleToRole']);
        Route::post('DestroyModuleOfRole', [ModuleController::class, 'destroySubModuleOfRole']);
    }));

    Route::group(['prefix' => '/RegionalDirectorates', 'middleware' => 'RegionalDirectoratesMid', 'as' => 'rd.'], function () {

        Route::get('/', [RDController::class, 'index'])->name('Index');
        Route::get('AddRD', [RDController::class, 'addRd'])->name('addRd');
        Route::post('AddRD', [RDController::class, 'insertRd'])->name('InsertRd');
        Route::post('DestroyRD', [RDController::class, 'dersroyRd']);
        Route::get('EditRd/{id}', [RDController::class, 'editRd'])->name('EditRd');
        Route::post('EditRd/{id}', [RDController::class, 'updateRd'])->name('UpdateRd');
        Route::get('RegionDistrict/{id?}', [RDController::class, 'regionDistrict'])->name('RegionDistrict');
        Route::get('Report', [RDController::class, 'getReport'])->name('Report');

        ### ==> Ajax Info <== ###
        Route::post('Info', [RDController::class, 'regionInfo'])->name('Info');
        Route::post('RegionalDistricts', [RDController::class, 'regionalDistricts'])->name('RegionalDistricts');
        Route::post('AddRegDistrict', [RDController::class, 'addRegDistrict'])->name('AddRegDistrict');
        Route::get('ListRegionalDistricts', [RDController::class, 'listRegionalDistricts'])->name('ListRegionalDistricts');
        Route::get('ListIdleDistricts', [RDController::class, 'listIdleDistricts'])->name('ListIdleDistricts');
        Route::get('ListIdleAgenciesRegion', [RDController::class, 'listIdleAgenciesRegion'])->name('ListIdleAgenciesRegion');
        Route::get('ListIdleAgenciesTC', [RDController::class, 'listIdleAgenciesTc'])->name('ListIdleAgenciesTC');
        Route::post('DestroyRDDistrict', [RDController::class, 'destroyRdDistrict']);
    });

});


Route::get('not.yet', [DefaultController::class, 'notyet'])->name('not.yet');
