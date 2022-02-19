<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Marketing\SenderCurrentController;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {

    Route::prefix('Customers')->group(function () {
        #GM ALL currents
        Route::get('GetAllCustomers', [SenderCurrentController::class, 'getAllCustomers'])->name('customer.gm.getAllCustomers');
        Route::get('/', [SenderCurrentController::class, 'customersIndex'])->name('customers.index');
        Route::get('GetAllCustomers', [SenderCurrentController::class, 'getAllCustomers'])->name('customer.gm.getAllCustomers');
        Route::post('GetCustomerInfo', [SenderCurrentController::class, 'getCustomerById']);
        Route::delete('/Delete/{id}', [SenderCurrentController::class, 'deleteCustomer']);
    });

});
