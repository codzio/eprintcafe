<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\admin\Auth;
use App\Http\Controllers\admin\MediaManager;
use App\Http\Controllers\admin\Users;
use App\Http\Controllers\admin\Roles;
use App\Http\Controllers\admin\SiteSettings;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix(config('admin.path'))->middleware('web')->group(function () {
    
    Route::get('/', [Auth::class,'index'])->name('adminLogin');
    Route::get('/two-step-verification', [Auth::class,'twoStepVerify'])->name('adminTwoStep');
    Route::get('/forgot-password', [Auth::class,'forgotPassword'])->name('adminForgotPassword');
    Route::get('/reset-password/{token}', [Auth::class,'resetPassword'])->name('adminResetPassword');

    Route::post('/doLogin', [Auth::class,'doLogin'])->name('adminDoLogin');
    Route::post('/doCheckTwoStep', [Auth::class,'doCheckTwoStep'])->name('adminDoCheckTwoStep');
    Route::post('/doResendOTP', [Auth::class,'doResendOTP'])->name('adminDo/ResendOTP');
    Route::post('/doForgotPassword', [Auth::class,'doForgotPassword'])->name('adminDoForgotPassword');
    Route::post('/doResetPassword', [Auth::class,'doResetPassword'])->name('adminDoResetPassword');

    Route::middleware('adminSessionCheck')->group(function(){

        Route::get('/logout', [Auth::class,'logout'])->name('adminLogout');
        Route::get('/dashboard', [Auth::class,'dashboard'])->name('adminDashboard');
        Route::get('/account-settings', [Auth::class,'accountSettings'])->name('adminAccountSettings');
        Route::post('/doUpdateProfile', [Auth::class,'doUpdateProfile'])->name('adminDoUpdateProfile');
        Route::post('/doUpdateEmail', [Auth::class,'doUpdateEmail'])->name('adminDoUpdateEmail');
        Route::post('/doChangePassword', [Auth::class,'doChangePassword'])->name('adminDoChangePassword');
        Route::post('/doUpdateTwoStep', [Auth::class,'doUpdateTwoStep'])->name('adminDoUpdateTwoStep');

        //Media
        Route::prefix('media')->group(function() {
            Route::get('/', [MediaManager::class, 'index'])->name('adminMedia');
            Route::get('/getMedia', [MediaManager::class, 'getMedia'])->name('adminGetMedia');
            Route::post('/doUpload', [MediaManager::class, 'doUpload'])->name('adminMediaDoUpload');
            Route::post('/doUpdateAlt', [MediaManager::class, 'doUpdateAlt'])->name('adminDoUpdateAlt');
            Route::post('/doSingleMediaDel', [MediaManager::class, 'doSingleMediaDel'])->name('adminDoSingleMediaDel');
            Route::post('/doMediaBulkDelete', [MediaManager::class, 'doMediaBulkDelete'])->name('adminDoMediaBulkDelete');
        });

        //Users
        Route::prefix('users')->group(function() {
            Route::get('/', [Users::class, 'index'])->name('adminUsers');
            Route::get('/get', [Users::class, 'get'])->name('getAdminUsers');

            Route::get('/add', [Users::class, 'add'])->name('adminAddUser');
            Route::get('/edit/{id}', [Users::class, 'edit'])->name('adminEditUser');

            Route::post('/doAdd', [Users::class, 'doAdd'])->name('adminDoAddUser');
            Route::post('/doUpdate', [Users::class, 'doUpdate'])->name('adminDoUpdateUser');
            Route::post('/doDelete', [Users::class, 'doDelete'])->name('adminDeleteUser');
            Route::post('/doBulkDelete', [Users::class, 'doBulkDelete'])->name('adminBulkDeleteUser');
        });

        //Roles & Permission
        Route::prefix('roles')->group(function() {
            Route::get('/', [Roles::class, 'index'])->name('adminRoles');
            Route::get('/get', [Roles::class, 'get'])->name('getAdminRoles');
            Route::get('/add', [Roles::class, 'add'])->name('adminAddRole');
            Route::get('/edit/{id}', [Roles::class, 'edit'])->name('adminEditRole');

            Route::post('/doAdd', [Roles::class, 'doAdd'])->name('adminDoAddRole');
            Route::post('/doUpdate', [Roles::class, 'doUpdate'])->name('adminDoUpdateRole');
            Route::post('/doDelete', [Roles::class, 'doDelete'])->name('adminDeleteRole');
            Route::post('/doBulkDelete', [Roles::class, 'doBulkDelete'])->name('adminBulkDeleteRole');
        });

        //Site Settings
        Route::prefix('site-settings')->group(function() {
            Route::get('/', [SiteSettings::class, 'index'])->name('adminSiteSetting');
            Route::post('/doUpdateGeneralSetting', [SiteSettings::class, 'doUpdateGeneralSetting'])->name('adminDoUpdateGeneralSetting');
            Route::post('/doUpdateScripts', [SiteSettings::class, 'doUpdateScripts'])->name('adminDoUpdateScripts');
            Route::post('/doUpdateSocialIcons', [SiteSettings::class, 'doUpdateSocialIcons'])->name('adminDoUpdateSocialIcons');
            Route::post('/doUpdateSMTP', [SiteSettings::class, 'doUpdateSMTP'])->name('adminDoUpdateSMTP');
        });

    });

});