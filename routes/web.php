<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\admin\Auth;
use App\Http\Controllers\admin\MediaManager;
use App\Http\Controllers\admin\Users;
use App\Http\Controllers\admin\Roles;
use App\Http\Controllers\admin\SiteSettings;

use App\Http\Controllers\admin\Category;
use App\Http\Controllers\admin\PaperSize;
use App\Http\Controllers\admin\PaperType;
use App\Http\Controllers\admin\Binding;
use App\Http\Controllers\admin\Lamination;

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

        //Category
        Route::prefix('category')->group(function() {
            Route::get('/', [Category::class, 'index'])->name('adminCategory');
            Route::get('/get', [Category::class, 'get'])->name('getAdminCategory');

            Route::get('/add', [Category::class, 'add'])->name('adminAddCategory');
            Route::get('/edit/{id}', [Category::class, 'edit'])->name('adminEditCategory');

            Route::post('/doAdd', [Category::class, 'doAdd'])->name('adminDoAddCategory');
            Route::post('/doUpdate', [Category::class, 'doUpdate'])->name('adminDoUpdateCategory');
            Route::post('/doDelete', [Category::class, 'doDelete'])->name('adminDeleteCategory');
            Route::post('/doBulkDelete', [Category::class, 'doBulkDelete'])->name('adminBulkDeleteCategory');
        });

        //Paper Size
        Route::prefix('paper-size')->group(function() {
            Route::get('/', [PaperSize::class, 'index'])->name('adminPaperSize');
            Route::get('/get', [PaperSize::class, 'get'])->name('getAdminPaperSize');

            Route::get('/add', [PaperSize::class, 'add'])->name('adminAddPaperSize');
            Route::get('/edit/{id}', [PaperSize::class, 'edit'])->name('adminEditPaperSize');

            Route::post('/doAdd', [PaperSize::class, 'doAdd'])->name('adminDoAddPaperSize');
            Route::post('/doUpdate', [PaperSize::class, 'doUpdate'])->name('adminDoUpdatePaperSize');
            Route::post('/doDelete', [PaperSize::class, 'doDelete'])->name('adminDeletePaperSize');
            Route::post('/doBulkDelete', [PaperSize::class, 'doBulkDelete'])->name('adminBulkDeletePaperSize');
        });

        //Paper Type
        Route::prefix('paper-type')->group(function() {
            Route::get('/', [PaperType::class, 'index'])->name('adminPaperType');
            Route::get('/get', [PaperType::class, 'get'])->name('getAdminPaperType');

            Route::get('/add', [PaperType::class, 'add'])->name('adminAddPaperType');
            Route::get('/edit/{id}', [PaperType::class, 'edit'])->name('adminEditPaperType');

            Route::post('/doAdd', [PaperType::class, 'doAdd'])->name('adminDoAddPaperType');
            Route::post('/doUpdate', [PaperType::class, 'doUpdate'])->name('adminDoUpdatePaperType');
            Route::post('/doDelete', [PaperType::class, 'doDelete'])->name('adminDeletePaperType');
            Route::post('/doBulkDelete', [PaperType::class, 'doBulkDelete'])->name('adminBulkDeletePaperType');
        });

        //Binding
        Route::prefix('binding')->group(function() {
            Route::get('/', [Binding::class, 'index'])->name('adminBinding');
            Route::get('/get', [Binding::class, 'get'])->name('getAdminBinding');

            Route::get('/add', [Binding::class, 'add'])->name('adminAddBinding');
            Route::get('/edit/{id}', [Binding::class, 'edit'])->name('adminEditBinding');

            Route::post('/doAdd', [Binding::class, 'doAdd'])->name('adminDoAddBinding');
            Route::post('/doUpdate', [Binding::class, 'doUpdate'])->name('adminDoUpdateBinding');
            Route::post('/doDelete', [Binding::class, 'doDelete'])->name('adminDeleteBinding');
            Route::post('/doBulkDelete', [Binding::class, 'doBulkDelete'])->name('adminBulkDeleteBinding');
        });

        //Lamination
        Route::prefix('lamination')->group(function() {
            Route::get('/', [Lamination::class, 'index'])->name('adminLamination');
            Route::get('/get', [Lamination::class, 'get'])->name('getAdminLamination');

            Route::get('/add', [Lamination::class, 'add'])->name('adminAddLamination');
            Route::get('/edit/{id}', [Lamination::class, 'edit'])->name('adminEditLamination');

            Route::post('/doAdd', [Lamination::class, 'doAdd'])->name('adminDoAddLamination');
            Route::post('/doUpdate', [Lamination::class, 'doUpdate'])->name('adminDoUpdateLamination');
            Route::post('/doDelete', [Lamination::class, 'doDelete'])->name('adminDeleteLamination');
            Route::post('/doBulkDelete', [Lamination::class, 'doBulkDelete'])->name('adminBulkDeleteLamination');
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