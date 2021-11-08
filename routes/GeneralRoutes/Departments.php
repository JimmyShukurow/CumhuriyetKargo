<?php

use App\Http\Controllers\Backend\Department\DepartmentController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {
    Route::group(['prefix' => 'Departments', 'middleware' => ['DepartmentsMid'], 'as' => 'Departments.'], function () {
        Route::get('DepartmentRole', [DepartmentController::class, 'departmentRole'])->name('Roles');
        Route::post('GetRoles/{direktive?}', [DepartmentController::class, 'getRoles'])->name('getRoles');
        Route::post('GiveRole', [DepartmentController::class, 'giveRole'])->name('giveRole');
        Route::get('ListRoleDepartments', [DepartmentController::class, 'listRoleDepartments'])->name('ListRoleDepartments');
        Route::post('DestroyRoleDepartment', [DepartmentController::class, 'destroyRoleDepartment']);
    });
    Route::resource('Departments', DepartmentController::class)
        ->middleware('DepartmentsMid');
});
