<?php

use App\Http\Controllers\Backend\Module\ModuleController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['CheckAuth', 'CheckStatus']], function () {
    Route::group(
        ['prefix' => '/Module', 'middleware' => ['ModulesMid']],
        (function () {
            Route::name('module.')->group(function () {
                Route::get('/', [ModuleController::class, 'index'])->name('Index');

                Route::get('/SystemUpdate/', [ModuleController::class, 'systemUpdateIndex'])->name('systemUpdate.Index');
                Route::get('/SystemUpdate/Create', [ModuleController::class, 'systemUpdateCreate'])->name('systemUpdate.Create');
                Route::post('/SystemUpdate/Insert', [ModuleController::class, 'systemUpdateInsert'])->name('systemUpdate.Insert');
                Route::get('/SystemUpdate/Edit/{id}', [ModuleController::class, 'systemUpdateEdit'])->name('systemUpdate.Edit');
                Route::post('/SystemUpdate/Update/{id}', [ModuleController::class, 'systemUpdateUpdate'])->name('systemUpdate.Update');
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

            Route::post('ChangeTheme', [ModuleController::class, 'changeTheme'])->name('changeTheme');
        })
    );
});
