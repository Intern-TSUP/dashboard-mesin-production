<?php

use Illuminate\Support\Facades\Route;

// Admin Zone
Route::prefix('admin')->name('admin.')->middleware(['auth', 'CheckJobLvlPermission'])->group(function () {
    // Custom Roles
    Route::prefix('roles')->name('roles.')->middleware(['auth'])->group(function () {
        Route::get('dt', [App\Http\Controllers\System\CustomRoles\CustomRolesController::class, 'getDataTable'])->name('getDT');
        Route::get('', [App\Http\Controllers\System\CustomRoles\CustomRolesController::class, 'index'])->name('index');
        Route::get('create', [App\Http\Controllers\System\CustomRoles\CustomRolesController::class, 'create'])->name('create');
        Route::post('store', [App\Http\Controllers\System\CustomRoles\CustomRolesController::class, 'store'])->name('store');
        Route::get('edit/{id}', [App\Http\Controllers\System\CustomRoles\CustomRolesController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [App\Http\Controllers\System\CustomRoles\CustomRolesController::class, 'update'])->name('update');
        Route::post('destroy', [App\Http\Controllers\System\CustomRoles\CustomRolesController::class, 'destroy'])->name('destroy');

        Route::prefix('assigned')->name('assigned.')->group(function () {
            Route::get('dt', [App\Http\Controllers\System\CustomRoles\AssignedCustomRolesController::class, 'getDataTable'])->name('getDT');
            Route::get('{role}/{id}', [App\Http\Controllers\System\CustomRoles\AssignedCustomRolesController::class, 'index'])->name('index');
            Route::get('{role}/{id}/getEmployee', [App\Http\Controllers\System\CustomRoles\AssignedCustomRolesController::class, 'hrisGetEmployee'])->name('getHrisEmployee');
            Route::post('{role}/{id}/store', [App\Http\Controllers\System\CustomRoles\AssignedCustomRolesController::class, 'store'])->name('store');
            Route::delete('{role}/{id}', [App\Http\Controllers\System\CustomRoles\AssignedCustomRolesController::class, 'destroy'])->name('destroy');
        });
    });

    // permission role
    Route::prefix('permission')->name('permission.')->group(function () {
        Route::get('', [App\Http\Controllers\System\Permission\PermissionController::class, 'index'])->name('index');
        Route::get('getData', [App\Http\Controllers\System\Permission\PermissionController::class, 'getDataTablePermission'])->name('getDataTable');
        Route::get('create', [App\Http\Controllers\System\Permission\PermissionController::class, 'create'])->name('create');
        Route::post('store', [App\Http\Controllers\System\Permission\PermissionController::class, 'store'])->name('store');
        Route::get('edit/{id}', [App\Http\Controllers\System\Permission\PermissionController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [App\Http\Controllers\System\Permission\PermissionController::class, 'update'])->name('update');
        Route::post('destroy', [App\Http\Controllers\System\Permission\PermissionController::class, 'destroy'])->name('destroy');
    });

    // permission line
    Route::prefix('permissionLine')->name('permissionLine.')->group(function () {
        Route::get('', [App\Http\Controllers\System\Permission\PermissionLineController::class, 'index'])->name('index');
        Route::get('getData', [App\Http\Controllers\System\Permission\PermissionLineController::class, 'getDataTablePermission'])->name('getDataTable');
        Route::get('create', [App\Http\Controllers\System\Permission\PermissionLineController::class, 'create'])->name('create');
        Route::post('store', [App\Http\Controllers\System\Permission\PermissionLineController::class, 'store'])->name('store');
        Route::get('edit/{id}', [App\Http\Controllers\System\Permission\PermissionLineController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [App\Http\Controllers\System\Permission\PermissionLineController::class, 'update'])->name('update');
        Route::post('destroy', [App\Http\Controllers\System\Permission\PermissionLineController::class, 'destroy'])->name('destroy');
        Route::get('createUser/{id}', [App\Http\Controllers\System\Permission\PermissionLineController::class, 'createUser'])->name('createUser');
        Route::get('getDataUser', [App\Http\Controllers\System\Permission\PermissionLineController::class, 'getDataTableUser'])->name('getDataTableUser');
        Route::post('storeUser/{id}', [App\Http\Controllers\System\Permission\PermissionLineController::class, 'storeUser'])->name('storeUser');
        Route::get('editUser/{id}', [App\Http\Controllers\System\Permission\PermissionLineController::class, 'editUser'])->name('editUser');
        Route::post('updateUser/{id}', [App\Http\Controllers\System\Permission\PermissionLineController::class, 'updateUser'])->name('updateUser');
        Route::post('destroyUser', [App\Http\Controllers\System\Permission\PermissionLineController::class, 'destroyUser'])->name('destroyUser');
        Route::get('getAvailableUsers', [App\Http\Controllers\System\Permission\PermissionLineController::class, 'getAvailableUsers'])->name('getAvailableUsers');
    });

    // subdepartment
    Route::prefix('department')->name('department.')->group(function () {
        Route::get('', [App\Http\Controllers\System\Department\DepartmentController::class, 'index'])->name('index');
        Route::get('getDataTableDepartment', [App\Http\Controllers\System\Department\DepartmentController::class, 'getDataTableDepartment'])->name('getDataTableDepartment');
    });

    // Setting
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('', [App\Http\Controllers\System\Settings\SettingsController::class, 'index'])->name('index');
        Route::post('store', [App\Http\Controllers\System\Settings\SettingsController::class, 'store'])->name('store');
    });

    // User
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('index');
        Route::get('getData', [App\Http\Controllers\Admin\UserController::class, 'getDataTableUser'])->name('getDataTableUser');
        Route::get('create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('create');
        Route::post('store', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('store');
        Route::get('edit/{id}', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('update');
        Route::post('destroy', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('destroy');
        Route::get('search', [App\Http\Controllers\Admin\UserController::class, 'search'])->name('search');
    });

    Route::prefix('localUser')->name('localUser.')->group(function () {
        Route::get('', [App\Http\Controllers\System\LocalUser\LocalUserController::class, 'index'])->name('index');
        Route::get('getData', [App\Http\Controllers\System\LocalUser\LocalUserController::class, 'getData'])->name('getData');
        Route::get('searchEmployee', [App\Http\Controllers\System\LocalUser\LocalUserController::class, 'searchEmployee'])->name('searchEmployee');
        Route::post('store', [App\Http\Controllers\System\LocalUser\LocalUserController::class, 'store'])->name('store');
        Route::post('update', [App\Http\Controllers\System\LocalUser\LocalUserController::class, 'update'])->name('update');
        Route::post('destroy', [App\Http\Controllers\System\LocalUser\LocalUserController::class, 'destroy'])->name('destroy');
        Route::post('resetPassword', [App\Http\Controllers\System\LocalUser\LocalUserController::class, 'resetPassword'])->name('resetPassword');
    });

    // user Assigned to Custom Roles
    Route::prefix('user-has-custom-role')->name('user-has-custom-role.')->group(function () {
        Route::get('{role}/{id}', [App\Http\Controllers\System\User\UserCustomRolesController::class, 'index'])->name('index');
        Route::get('{role}/{id}/dt', [App\Http\Controllers\System\User\UserCustomRolesController::class, 'getDataTable'])->name('getDT');
        Route::get('{role}/{id}/getEmployee', [App\Http\Controllers\System\User\UserCustomRolesController::class, 'hrisGetEmployee'])->name('getHrisEmployee');
        Route::post('{role}/{id}/store', [App\Http\Controllers\System\User\UserCustomRolesController::class, 'store'])->name('store');
        Route::delete('{id}', [App\Http\Controllers\System\User\UserCustomRolesController::class, 'destroy'])->name('destroy');
        Route::put('update/{id}', [App\Http\Controllers\System\User\UserCustomRolesController::class, 'update'])->name('update');
    });
});
