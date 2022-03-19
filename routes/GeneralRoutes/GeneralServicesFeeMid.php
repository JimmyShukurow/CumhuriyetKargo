<?php

use App\Http\Controllers\Backend\ServiceFee\ServiceFeeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\ServiceFee\AdditionalServicesController;
use App\Http\Controllers\Backend\ServiceFee\DesiListController;
use App\Http\Controllers\Backend\Marketing\DistanceController;

Route::group(
    ['middleware' => ['CheckAuth', 'CheckStatus']],
    function () {
        # ==> Services Fee Transaction START

        # ==> Services Fee Transaction END
    }
);
