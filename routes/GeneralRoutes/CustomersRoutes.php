<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Marketing\SenderCurrentController;
use App\Http\Controllers\Customer\CustomerController;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {


    Route::group(['prefix' => 'Customers', 'as' => 'customers.'], function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('GetAllCustomers', [CustomerController::class, 'getAllCustomers']);
        Route::post('GetCustomerInfo', [CustomerController::class, 'getCustomerById']);
        Route::delete('/Delete/{id}', [CustomerController::class, 'deleteCustomer']);

        Route::get('Create/{type}', [CustomerController::class, 'create'])->name('create');
        Route::post('AjaxTransaction/{val}', [CustomerController::class, 'ajaxTransaction']);
    });


});
