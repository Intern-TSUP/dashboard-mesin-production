<?php

use Illuminate\Support\Facades\Route;

// Admin Zone
Route::prefix('admin')->name('admin.')->middleware(['auth', 'CheckJobLvlPermission'])->group(function () {
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
    Route::prefix('subdepartment')->name('subdept.')->group(function () {
        Route::get('', [App\Http\Controllers\System\Department\SubDepartmentController::class, 'index'])->name('index');
        Route::get('dt_subdept', [App\Http\Controllers\System\Department\SubDepartmentController::class, 'dt_subdept'])->name('dt_subdept');
        Route::post('store', [App\Http\Controllers\System\Department\SubDepartmentController::class, 'store'])->name('store');
        Route::post('edit', [App\Http\Controllers\System\Department\SubDepartmentController::class, 'edit'])->name('edit');
        Route::post('update', [App\Http\Controllers\System\Department\SubDepartmentController::class, 'update'])->name('update');
        Route::post('destroy', [App\Http\Controllers\System\Department\SubDepartmentController::class, 'destroy'])->name('destroy');
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
});
