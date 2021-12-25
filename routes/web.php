<?php

use App\Http\Controllers\Admin\AuthAdminController;
use App\Http\Controllers\Admin\SupervisorController;
use App\Http\Controllers\Supervisor\AuthSupervisorController;
use App\Http\Controllers\Supervisor\CategoryController;
use App\Http\Controllers\Supervisor\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/',function (){
   return view('welcome');
});

Route::as('admin.')->prefix('admin')->group(function () {
    Route::middleware(['guest'])->group(function () {
        Route::get('login', [AuthAdminController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthAdminController::class, 'login'])->name('login');
        Route::get('rest/password', [AuthAdminController::class, 'resetPassword'])->name('reset.password');
        Route::post('rest/password', [AuthAdminController::class, 'postResetPassword'])->name('reset.password');
        Route::get('rest/password/{token}', [AuthAdminController::class, 'reset'])->name('reset');
        Route::post('rest/password/{token}', [AuthAdminController::class, 'postReset'])->name('reset');
    });

    Route::middleware(['auth'])->group(function () {
        Route::redirect('/admin', '/admin/home');
        Route::get('logout', [AuthAdminController::class,'logout'])->name('logout');

        Route::get('home', function (){
           return view('admin.home');
        })->name('home');

        Route::post('supervisor/change-password',[SupervisorController::class,'changePassword'])->name('supervisor.change-password');
        Route::post('supervisor/multiple-delete',[SupervisorController::class,'multipleDelete'])->name('supervisor.multiple-delete');
        Route::get('supervisor/status/{id}',[SupervisorController::class,'updatedStatus'])->name('supervisor.status');
        Route::resource('supervisor',SupervisorController::class);
    });

});

Route::as('supervisor.')->prefix('supervisor')->group(function () {
    Route::middleware(['guest:supervisor'])->group(function () {
        Route::get('login', [AuthSupervisorController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthSupervisorController::class, 'login'])->name('login');
        Route::get('rest/password', [AuthSupervisorController::class, 'resetPassword'])->name('reset.password');
        Route::post('rest/password', [AuthSupervisorController::class, 'postResetPassword'])->name('reset.password');
        Route::get('rest/password/{token}', [AuthSupervisorController::class, 'reset'])->name('reset');
        Route::post('rest/password/{token}', [AuthSupervisorController::class, 'postReset'])->name('reset');
    });

    Route::middleware(['auth:supervisor','is_unblocked'])->group(function () {
        Route::redirect('/supervisor', '/supervisor/home');
        Route::get('logout', [AuthSupervisorController::class,'logout'])->name('logout');

        Route::get('home', function (){
            return view('supervisor.home');
        })->name('home');

        Route::post('category/multiple-delete',[CategoryController::class,'multipleDelete'])->name('category.multiple-delete');
        Route::resource('category',CategoryController::class);

        Route::post('product/multiple-delete',[ProductController::class,'multipleDelete'])->name('product.multiple-delete');
        Route::post('product/delete-images',[ProductController::class,'deleteImage'])->name('product.delete-images');
        Route::resource('product',ProductController::class);
    });

});
