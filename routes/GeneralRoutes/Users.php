<?php

use App\Http\Controllers\Backend\User\UserGM\UserController;
use App\Http\Controllers\Backend\Marketing\SenderCurrentController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {
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
        Route::post('GetCustomerInfo', [SenderCurrentController::class, 'getCustomerById']);
    }));
});
