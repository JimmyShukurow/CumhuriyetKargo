<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        Route::resourceVerbs([
            'create' => 'Create',
            'edit' => 'Edit'
        ]);

        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/Whoiswho.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/Maincargo.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/Users.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/Modules.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/RegionalDirectorates.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/ItAndNotifications.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/Operations.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/TransshipmentCenters.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/Agencies.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/AdminSystemSupport.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/Departments.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/Personel.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/Report.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/CargoBagsRoutes.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/GeneralServicesFeeMid.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/SystemSupport.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/SafeRoutes.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/OfficialReportsRoutes.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/AjaxRoutes.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/RegionRoutes.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/CustomersRoutes.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/ReportsRoutes.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/TcUsersRoutes.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/AgencyUsersRoutes.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/CarsRoutes.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/MarketingRoutes.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/DashboardRoutes.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/ExpeditionRoutes.php'));
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/GeneralRoutes/TutorialsRoutes.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(20)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
