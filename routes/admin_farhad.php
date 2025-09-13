<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\Farhad\RoleController;
use App\Http\Controllers\Backend\Farhad\UserController;
use App\Http\Controllers\Backend\Farhad\BrandController;
use App\Http\Controllers\Backend\Farhad\StatusController;
use App\Http\Controllers\Backend\Farhad\ProductController;
use App\Http\Controllers\Backend\Farhad\CategoryController;
use App\Http\Controllers\Backend\Farhad\DashboardController;
use App\Http\Controllers\Backend\Farhad\SystemSettingController;
use App\Http\Controllers\Backend\Farhad\ProfileSettingController;



Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {

    // Dashboard route
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Brands routes
    Route::get('brands', [BrandController::class, 'index'])->name('brands.index');
    Route::get('brands/create', [BrandController::class, 'create'])->name('brands.create');
    Route::post('brands', [BrandController::class, 'store'])->name('brands.store');
    Route::get('brands/{brand}', [BrandController::class, 'show'])->name('brands.show');
    Route::get('brands/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');
    Route::put('brands/{brand}', [BrandController::class, 'update'])->name('brands.update');
    Route::delete('brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');

    // Categories routes
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Products routes
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Systems routes
    Route::get('system/settings', [SystemSettingController::class, 'edit'])->name('system-settings.edit');
    Route::post('system/settings', [SystemSettingController::class, 'update'])->name('system-settings.update');
    // Mail settings routes
    Route::get('mail/settings', [SystemSettingController::class, 'editMailSettings'])->name('mail-settings.edit');
    Route::post('mail/settings', [SystemSettingController::class, 'updateMailSettings'])->name('mail-settings.update');

    // Profile settings routes
    Route::get('profile/settings', [ProfileSettingController::class, 'edit'])->name('profile-settings.edit');
    Route::post('profile/settings/{id}', [ProfileSettingController::class, 'update'])->name('profile-settings.update');

    // role routes
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // users routes
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('verify-password', [UserController::class, 'verifyPassword'])->name('verify-password'); //for verify password for delete


    //status
    // Route::post('/update-status', [StatusController::class, 'update'])->name('status.update')->middleware('can:status_update');
    Route::post('/update-status', [StatusController::class, 'update'])->name('status.update');

    // CKEditor image upload
    Route::post('/ckeditor/upload', [ProductController::class, 'uploadCkEditorImage'])->name('ckeditor.upload');
    Route::delete('/ckeditor/remove', [ProductController::class, 'removeCkEditorImage']) ->name('ckeditor.remove');
});
