<?php

namespace App\Http;

use App\Http\Middleware\Admin;
use App\Http\Middleware\AgenciesSpecialist;
use App\Http\Middleware\AuthSahareInfo;
use App\Http\Middleware\CheckAuth;
use App\Http\Middleware\CheckLogin;
use App\Http\Middleware\MidsOfCont\AgenciesMid;
use App\Http\Middleware\MidsOfCont\AgencySafeMid;
use App\Http\Middleware\MidsOfCont\DashboardGmMid;
use App\Http\Middleware\MidsOfCont\DepartmentsMid;
use App\Http\Middleware\MidsOfCont\GeneralSafeMid;
use App\Http\Middleware\MidsOfCont\GeneralServicesFeeMid;
use App\Http\Middleware\MidsOfCont\LocalLocationMid;
use App\Http\Middleware\MidsOfCont\MainCargoMid;
use App\Http\Middleware\MidsOfCont\ModulesMid;
use App\Http\Middleware\MidsOfCont\OfficialReports\ConfirmReport;
use App\Http\Middleware\MidsOfCont\OfficialReports\ConfirmReportMid;
use App\Http\Middleware\MidsOfCont\OfficialReports\ManageReportMid;
use App\Http\Middleware\MidsOfCont\RegionalDirectoratesMid;
use App\Http\Middleware\MidsOfCont\SenderCurrentsMid;
use App\Http\Middleware\MidsOfCont\ThemeMid;
use App\Http\Middleware\MidsOfCont\TransferCarsMid;
use App\Http\Middleware\MidsOfCont\Users\AgencyUserMid;
use App\Http\Middleware\MidsOfCont\Users\GmUsersMid;
use App\Http\Middleware\MidsOfCont\Users\TCUserMid;
use App\Http\Middleware\MidsOfCont\VariousCarsMid;
use App\Http\Middleware\Operation;
use App\Http\Middleware\PermissionOfCreateCargo;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use App\Http\Middleware\CheckStatus;
use App\Http\Middleware\MidsOfCont\AdminSystemSupportMid;
use App\Http\Middleware\MidsOfCont\ItAndNotificationMidX;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'ApiAuthenticate' => \App\Http\Middleware\ApiAuthenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'admin' => Admin::class,
        'AgenciesSpecialist' => AgenciesSpecialist::class,
        'Operation' => Operation::class,
        'CheckLogin' => CheckLogin::class,
        'CheckAuth' => CheckAuth::class,
        'CheckStatus' => CheckStatus::class,
        'PermissionOfCreateCargo' => PermissionOfCreateCargo::class,

        #Middlewares of Controllers
        'AgenciesMid' => AgenciesMid::class,
        'RegionalDirectoratesMid' => RegionalDirectoratesMid::class,
        'TransshipmentCenterMid' => RegionalDirectoratesMid::class,
        'ModulesMid' => ModulesMid::class,
        'GmUsersMid' => GmUsersMid::class,
        'AgencyUserMid' => AgencyUserMid::class,
        'TCUserMid' => TCUserMid::class,
        'ThemeMid' => ThemeMid::class,
        'DepartmentsMid' => DepartmentsMid::class,
        'AdminSystemSupportMid' => AdminSystemSupportMid::class,
        'TransferCarsMid' => TransferCarsMid::class,
        'VariousCarsMid' => VariousCarsMid::class,
        'LocalLocationMid' => LocalLocationMid::class,
        'GeneralServicesFeeMid' => GeneralServicesFeeMid::class,
        'SenderCurrentsMid' => SenderCurrentsMid::class,
        'ItAndNotificationMidX' => ItAndNotificationMidX::class,
        'MainCargoMid' => MainCargoMid::class,
        'ManageReportMid' => ManageReportMid::class,
        'GeneralSafeMid' => GeneralSafeMid::class,
        'AgencySafeMid' => AgencySafeMid::class,
        'DashboardGmMid' => DashboardGmMid::class,

    ];
}
