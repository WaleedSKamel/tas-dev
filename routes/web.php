<?php

use App\Http\Controllers\Admin\AuthAdminController;
use App\Http\Controllers\Admin\SupervisorController;
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

Route::as('admin.')->group(function () {
    Route::middleware(['guest'])->group(function () {
        Route::get('login', [AuthAdminController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthAdminController::class, 'login'])->name('login');
        Route::get('rest/password', [AuthAdminController::class, 'resetPassword'])->name('reset.password');
        Route::post('rest/password', [AuthAdminController::class, 'postResetPassword'])->name('reset.password');
        Route::get('rest/password/{token}', [AuthAdminController::class, 'reset'])->name('reset');
        Route::post('rest/password/{token}', [AuthAdminController::class, 'postReset'])->name('reset');
    });

    Route::middleware(['auth'])->prefix('admin')->group(function () {
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
