<?php

use Illuminate\Support\Facades\Route;

//Frontend
use App\Http\Controllers\Home;
use App\Http\Controllers\PriceCalculator;
use App\Http\Controllers\Cron;
use App\Http\Controllers\Customer;
use App\Http\Controllers\Cart;
use App\Http\Controllers\Checkout;
use App\Http\Controllers\Upload;

//Backend
use App\Http\Controllers\admin\Auth;
use App\Http\Controllers\admin\MediaManager;
use App\Http\Controllers\admin\Users;
use App\Http\Controllers\admin\Roles;
use App\Http\Controllers\admin\SiteSettings;

use App\Http\Controllers\admin\Category;
use App\Http\Controllers\admin\Coupon;
use App\Http\Controllers\admin\Shipping;
use App\Http\Controllers\admin\Barcode;
use App\Http\Controllers\admin\PaperSize;
use App\Http\Controllers\admin\PaperType;
use App\Http\Controllers\admin\Binding;
use App\Http\Controllers\admin\Lamination;
use App\Http\Controllers\admin\Cover;
use App\Http\Controllers\admin\Gsm;
use App\Http\Controllers\admin\Product;
use App\Http\Controllers\admin\Pricing;
use App\Http\Controllers\admin\Contact;
use App\Http\Controllers\admin\Orders;
use App\Http\Controllers\admin\Customers;
use App\Http\Controllers\admin\AbandonedCart;

use App\Http\Controllers\admin\LandingPageEnquiry;

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

Route::prefix('/')->group(function() {
    Route::get('/', [Home::class, 'index'])->name('homePage');
    Route::get('/about-us', [Home::class, 'about'])->name('aboutPage');
    Route::get('/pay-now/{orderid}', [Home::class, 'payNow'])->name('payNow');

    Route::get('/upload', [Upload::class, 'index'])->name('uploadPage');

    Route::get('/privacy-policy', [Home::class, 'privacyPolicy'])->name('privacyPolicyPage');
    Route::get('/return-policy', [Home::class, 'returnPolicy'])->name('returnPolicyPage');
    Route::get('/shipping-policy', [Home::class, 'shippingPolicy'])->name('shippingPolicyPage');
    Route::get('/cancellation-policy', [Home::class, 'cancellationPolicy'])->name('cancellationPolicyPage');
    Route::get('/terms-and-conditions', [Home::class, 'termsAndCondition'])->name('termsAndConditionPage');

    Route::get('/login', [Customer::class, 'login'])->name('loginPage');
    Route::get('/register', [Customer::class, 'register'])->name('registerPage');
    Route::get('/track-orders', [Home::class, 'trackOrders'])->name('trackOrdersPage');
    Route::post('/doTrackOrder', [Home::class, 'doTrackOrder'])->name('doTrackOrder');

    Route::get('/verify-account', [Customer::class, 'verifyAccount'])->name('verifyAccountPage');
    Route::post('/doVerifyAccount', [Customer::class, 'doVerifyAccount'])->name('doVerifyAccount');
    Route::post('/doResendRegisOtp', [Customer::class, 'doResendRegisOtp'])->name('doResendRegisOtp');
    Route::post('/doResendForgotPassOtp', [Customer::class, 'doResendForgotPassOtp'])->name('doResendForgotPassOtp');


    Route::get('/forgot-password', [Customer::class, 'forgotPassword'])->name('forgotPasswordPage');
    Route::post('/doForgotPassword', [Customer::class, 'doForgotPassword'])->name('doForgotPassword');
    // Route::get('/reset-password/{token}', [Customer::class,'resetPassword'])->name('customerResetPassword');
    Route::get('/reset-password', [Customer::class,'resetPassword'])->name('customerResetPassword');

    Route::any('/payumoney', [Home::class, 'payumoney'])->name('payumoneyPage');
    Route::any('/payment-response', [Home::class, 'paymentResponse'])->name('paymentResponse');
    Route::any('/paynow-response', [Home::class, 'paynowResponse'])->name('paynowResponse');

    Route::any('/thank-you', [Home::class, 'thankyou'])->name('thankyouPage');
    Route::any('/payment-failed', [Home::class, 'paymentFail'])->name('paymentFailPage');

    // Route::get('/upload', [Home::class, 'upload'])->name('uploadPage');
    // Route::post('/doUploadDropbox', [Home::class, 'doUploadDropbox'])->name('doUploadDropbox');

    Route::post('/doResetPassword', [Customer::class,'doResetPassword'])->name('customerDoResetPassword');

    Route::post('/doRegister', [Customer::class, 'doRegister'])->name('doRegister');
    Route::post('/doLogin', [Customer::class, 'doLogin'])->name('doLogin');
    Route::get('/contact-us', [Home::class, 'contact'])->name('contactPage');
    Route::get('/cart', [Cart::class, 'index'])->name('cartPage');
    Route::post('/doContact', [Home::class, 'getContact'])->name('getContact');

    Route::get('/abandoned-cart', [Cron::class, 'abdCart'])->name('abdCart');
    

    Route::get('/price-calculator', [PriceCalculator::class, 'index'])->name('priceCalc');
    Route::post('/getOptionsByCat', [PriceCalculator::class, 'getOptionsByCat'])->name('getOptionsByCat');
    Route::post('/getOptionsByProduct', [PriceCalculator::class, 'getOptionsByProduct'])->name('getOptionsByProduct');

    Route::post('/doSaveLandingPageLead', [Home::class, 'doSaveLandingPageLead'])->name('doSaveLandingPageLead');
    Route::get('/submit-lead-form', [Home::class, 'submitLeadFormPage'])->name('submitLeadFormPage');

    Route::get('/testEmail', [Home::class, 'testEmail'])->name('testEmail');
    Route::get('/testSms', [Home::class, 'testSms'])->name('testSms');
    Route::get('/testPayu', [Home::class, 'testPayu'])->name('testPayu');
});

Route::prefix('/upload')->middleware('customerSessionCheck')->group(function(){
    Route::post('/doUploadFiles', [Upload::class, 'doUploadFiles'])->name('doUploadFiles');
    Route::post('/getProductPricing', [Upload::class, 'getProductPricing'])->name('getProductPricing');
    Route::post('/doUpdateCart', [Upload::class, 'doUpdateCart'])->name('doUpdateCart');
    Route::post('/getTab4Data', [Upload::class, 'getTab4Data'])->name('getTab4Data');
    Route::post('/doApplyPromo', [Upload::class, 'doApplyPromo'])->name('applyUploadPromo');
    Route::post('/doPlaceOrder', [Upload::class, 'doPlaceOrder'])->name('uploadPlaceOrder');
});

Route::prefix('/customer')->middleware('customerSessionCheck')->group(function(){
    Route::get('/dashboard', [Customer::class, 'dashboard'])->name('customerDashboard');
    Route::get('/logout', [Customer::class, 'logout'])->name('customerLogout');
    Route::post('/doChangePassword', [Customer::class,'doChangePassword'])->name('customerChangePassword');
    Route::post('/update-account-details', [Customer::class,'doUpdateAccDetails'])->name('doUpdateAccDetails');
    Route::post('/doSaveShippingAddress', [Customer::class, 'doSaveShippingAddress'])->name('doSaveShippingAddress');
    Route::post('/doSaveBillingAddress', [Customer::class, 'doSaveBillingAddress'])->name('doSaveBillingAddress');
});

Route::prefix('/category')->group(function() {
    Route::get('/{slug}', [Home::class, 'category'])->name('categoryPage');
});

Route::prefix('/products')->group(function() {
    Route::post('/pricing', [Home::class, 'getPricing'])->name('getPricing');
    Route::get('/{slug}', [Home::class, 'product'])->name('productPage');
});

Route::prefix('/checkout')->group(function() {
    Route::get('/', [Checkout::class, 'index'])->name('checkoutPage');

    Route::post('/doUploadDropbox', [Checkout::class, 'doUploadDropbox'])->name('doUploadDropbox');
    Route::post('/doDeleteDropbox', [Checkout::class, 'doDeleteDropbox'])->name('doDeleteDropbox');

    Route::post('/doSaveAddress', [Checkout::class, 'doSaveAddress'])->name('saveAddress');
    Route::post('/doPlaceOrder', [Checkout::class, 'doPlaceOrder'])->name('placeOrder');
    Route::get('/response', [Checkout::class, 'response'])->name('response');
});

Route::post('/checkPincode', [Home::class, 'checkPincode'])->name('checkPincode');
Route::post('/checkDocumentLink', [Home::class, 'checkDocumentLink'])->name('checkDocumentLink');
Route::post('/doAddToCart', [Cart::class, 'doAddToCart'])->name('addToCart');
Route::post('/doRemoveCartItem', [Cart::class, 'doRemoveCartItem'])->name('removeCartItem');
Route::post('/doUpdateCartItem', [Cart::class, 'doUpdateCartItem'])->name('updateCartItem');
Route::post('/doApplyPromo', [Cart::class, 'doApplyPromo'])->name('applyPromo');

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

        //Coupon
        Route::prefix('coupon')->group(function() {
            Route::get('/', [Coupon::class, 'index'])->name('adminCoupon');
            Route::get('/get', [Coupon::class, 'get'])->name('getAdminCoupon');

            Route::get('/add', [Coupon::class, 'add'])->name('adminAddCoupon');
            Route::get('/edit/{id}', [Coupon::class, 'edit'])->name('adminEditCoupon');

            Route::post('/doAdd', [Coupon::class, 'doAdd'])->name('adminDoAddCoupon');
            Route::post('/doUpdate', [Coupon::class, 'doUpdate'])->name('adminDoUpdateCoupon');
            Route::post('/doDelete', [Coupon::class, 'doDelete'])->name('adminDeleteCoupon');
            Route::post('/doBulkDelete', [Coupon::class, 'doBulkDelete'])->name('adminBulkDeleteCoupon');
        });

        //Shipping
        Route::prefix('shipping')->group(function() {
            Route::get('/', [Shipping::class, 'index'])->name('adminShipping');
            Route::get('/get', [Shipping::class, 'get'])->name('getAdminShipping');
            Route::get('/bulk-import', [Shipping::class, 'bulkImport'])->name('adminShippingBulkImport');
            Route::post('/doShippingBulkImport', [Shipping::class, 'doShippingBulkImport'])->name('adminDoShippingBulkImport');
            Route::get('/add', [Shipping::class, 'add'])->name('adminAddShipping');
            Route::get('/edit/{id}', [Shipping::class, 'edit'])->name('adminEditShipping');

            Route::post('/doAdd', [Shipping::class, 'doAdd'])->name('adminDoAddShipping');
            Route::post('/doUpdate', [Shipping::class, 'doUpdate'])->name('adminDoUpdateShipping');
            Route::post('/doDelete', [Shipping::class, 'doDelete'])->name('adminDeleteShipping');
            Route::post('/doBulkDelete', [Shipping::class, 'doBulkDelete'])->name('adminBulkDeleteShipping');
        });

        //Barcode
        Route::prefix('barcode')->group(function() {
            Route::get('/', [Barcode::class, 'index'])->name('adminBarcode');
            Route::get('/get', [Barcode::class, 'get'])->name('getAdminBarcode');
            Route::get('/bulk-import', [Barcode::class, 'bulkImport'])->name('adminBarcodeBulkImport');
            Route::post('/doBarcodeBulkImport', [Barcode::class, 'doBarcodeBulkImport'])->name('adminDoBarcodeBulkImport');
            Route::get('/add', [Barcode::class, 'add'])->name('adminAddBarcode');
            Route::get('/edit/{id}', [Barcode::class, 'edit'])->name('adminEditBarcode');

            Route::post('/doAdd', [Barcode::class, 'doAdd'])->name('adminDoAddBarcode');
            Route::post('/doUpdate', [Barcode::class, 'doUpdate'])->name('adminDoUpdateBarcode');
            Route::post('/doDelete', [Barcode::class, 'doDelete'])->name('adminDeleteBarcode');
            Route::post('/doBulkDelete', [Barcode::class, 'doBulkDelete'])->name('adminBulkDeleteBarcode');
        });

        //Contact
        Route::prefix('contact')->group(function() {
            Route::get('/', [Contact::class, 'index'])->name('adminContact');
            Route::get('/get', [Contact::class, 'get'])->name('getAdminContact');
            Route::post('/doDelete', [Contact::class, 'doDelete'])->name('adminDeleteContact');
            Route::post('/doBulkDelete', [Contact::class, 'doBulkDelete'])->name('adminBulkDeleteContact');
        });

        //Landing Page Enquiry
        Route::prefix('landing-page')->group(function() {
            Route::get('/', [LandingPageEnquiry::class, 'index'])->name('adminLandingPageEnquiry');
            Route::get('/get', [LandingPageEnquiry::class, 'get'])->name('getAdminLandingPageEnquiry');
            Route::get('/view/{id}', [LandingPageEnquiry::class, 'view'])->name('adminViewLandingPageEnquiry');
            Route::post('/doDelete', [LandingPageEnquiry::class, 'doDelete'])->name('adminDeleteLandingPageEnquiry');
            Route::post('/doBulkDelete', [LandingPageEnquiry::class, 'doBulkDelete'])->name('adminBulkDeleteLandingPageEnquiry');
        });

        //Orders
        Route::prefix('orders')->group(function() {
            Route::get('/', [Orders::class, 'index'])->name('adminOrders');
            Route::get('/deleted', [Orders::class, 'deletedOrders'])->name('adminDeletedOrders');

            Route::get('/get', [Orders::class, 'get'])->name('getAdminOrders');
            Route::get('/get-deleted-orders', [Orders::class, 'getDeletedOrders'])->name('getAdminDeletedOrders');

            Route::get('/add', [Orders::class, 'add'])->name('adminAddOrders');
            Route::get('/edit/{id}', [Orders::class, 'edit'])->name('adminEditOrders');

            Route::post('/getCustomerAddress', [Orders::class, 'getCustomerAddress'])->name('adminGetCustomerAddress');
            Route::post('/getPricing', [Orders::class, 'getPricing'])->name('adminGetPricing');

            Route::post('/doAdd', [Orders::class, 'doAdd'])->name('adminDoAddOrders');
            Route::post('/doUpdate', [Orders::class, 'doUpdate'])->name('adminDoUpdateOrders');
            Route::post('/doUpdateCustomCart', [Orders::class, 'doUpdateCustomCart'])->name('adminDoUpdateCustomCart');
            Route::post('/doUpdateSaveCustomOrder', [Orders::class, 'doUpdateSaveCustomOrder'])->name('adminDoUpdateSaveCustomOrder');

            Route::get('/view/{id}', [Orders::class, 'view'])->name('adminViewOrder');
            Route::get('/invoice/{id}', [Orders::class, 'invoice'])->name('adminInvoiceOrder');
            Route::get('/packing-slip/{id}', [Orders::class, 'packingSlip'])->name('adminPackingSlipOrder');
            Route::post('/doUpdateBarcode', [Orders::class, 'doUpdateBarcode'])->name('adminUpdateBarcode');
            Route::post('/doUpdateOrderStatus', [Orders::class, 'doUpdateOrderStatus'])->name('adminUpdateOrderStatus');
            Route::post('/doUpdatePickupOption', [Orders::class, 'doUpdatePickupOption'])->name('adminUpdatePickupOption');
            
            Route::post('/doDelete', [Orders::class, 'doDelete'])->name('adminDeleteOrder');
            Route::post('/doBulkDelete', [Orders::class, 'doBulkDelete'])->name('adminBulkDeleteOrder');

            Route::post('/doPerDelete', [Orders::class, 'doPerDelete'])->name('adminPerDeleteOrder');
            Route::post('/doPerBulkDelete', [Orders::class, 'doPerBulkDelete'])->name('adminBulkPerDeleteOrder');
            Route::post('/doBulkRestoreDelete', [Orders::class, 'doBulkRestoreDelete'])->name('adminBulkRestoreOrder');

            Route::post('/doSendInvoice', [Orders::class, 'doSendInvoice'])->name('adminDoSendInvoice');
        });

        //Abandoned Cart
        Route::prefix('abandoned-cart')->group(function() {
            Route::get('/', [AbandonedCart::class, 'index'])->name('adminAbandonedCart');
            Route::get('/get', [AbandonedCart::class, 'get'])->name('getAdminAbandonedCart');
            Route::get('/view/{id}', [AbandonedCart::class, 'view'])->name('adminViewAbandonedCart');
            Route::post('/moveToOrders', [AbandonedCart::class, 'moveToOrders'])->name('adminMoveToOrders');
        });

        //Customers
        Route::prefix('customers')->group(function() {
            Route::get('/', [Customers::class, 'index'])->name('adminCustomers');

            Route::get('/add', [Customers::class, 'add'])->name('adminAddCustomers');
            Route::post('/doAdd', [Customers::class, 'doAdd'])->name('adminDoAddCustomers');

            Route::get('/get', [Customers::class, 'get'])->name('getAdminCustomers');
            Route::get('/export', [Customers::class, 'export'])->name('customerExport');
            Route::get('/view/{id}', [Customers::class, 'view'])->name('adminViewCustomer');
            Route::get('/login/{id}', [Customers::class, 'login'])->name('adminUserLogin');
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
            Route::get('/get', [PaperType::class, 'get'])->name('getGetAdminPaperType');

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

        //Cover
        Route::prefix('cover')->group(function() {
            Route::get('/', [Cover::class, 'index'])->name('adminCover');
            Route::get('/get', [Cover::class, 'get'])->name('getAdminCover');

            Route::get('/add', [Cover::class, 'add'])->name('adminAddCover');
            Route::get('/edit/{id}', [Cover::class, 'edit'])->name('adminEditCover');

            Route::post('/doAdd', [Cover::class, 'doAdd'])->name('adminDoAddCover');
            Route::post('/doUpdate', [Cover::class, 'doUpdate'])->name('adminDoUpdateCover');
            Route::post('/doDelete', [Cover::class, 'doDelete'])->name('adminDeleteCover');
            Route::post('/doBulkDelete', [Cover::class, 'doBulkDelete'])->name('adminBulkDeleteCover');
        });

        //Gsm
        Route::prefix('gsm')->group(function() {
            Route::get('/', [Gsm::class, 'index'])->name('adminGsm');
            Route::get('/get', [Gsm::class, 'get'])->name('getAdminGsm');

            Route::get('/add', [Gsm::class, 'add'])->name('adminAddGsm');
            Route::get('/edit/{id}', [Gsm::class, 'edit'])->name('adminEditGsm');

            Route::post('/doAdd', [Gsm::class, 'doAdd'])->name('adminDoAddGsm');
            Route::post('/doUpdate', [Gsm::class, 'doUpdate'])->name('adminDoUpdateGsm');
            Route::post('/doDelete', [Gsm::class, 'doDelete'])->name('adminDeleteGsm');
            Route::post('/doBulkDelete', [Gsm::class, 'doBulkDelete'])->name('adminBulkDeleteGsm');
        });

        //Product
        Route::prefix('product')->group(function() {
            Route::get('/', [Product::class, 'index'])->name('adminProduct');
            Route::get('/bulk-update', [Product::class, 'bulkUpdate'])->name('adminProductBulkUpdate');
            Route::post('/doUpdateProductPricing', [Product::class, 'doUpdateProductPricing'])->name('adminDoUpdateProductPricing');
            Route::get('/get', [Product::class, 'get'])->name('getAdminProduct');

            Route::get('/add', [Product::class, 'add'])->name('adminAddProduct');
            Route::get('/edit/{id}', [Product::class, 'edit'])->name('adminEditProduct');

            Route::post('/doAdd', [Product::class, 'doAdd'])->name('adminDoAddProduct');
            Route::post('/doUpdate', [Product::class, 'doUpdate'])->name('adminDoUpdateProduct');
            Route::post('/doDelete', [Product::class, 'doDelete'])->name('adminDeleteProduct');
            Route::post('/doBulkDelete', [Product::class, 'doBulkDelete'])->name('adminBulkDeleteProduct');
        });

        //Pricing
        Route::prefix('pricing')->group(function() {
            
            // Route::get('/edit/{productId}/{pricingId}', [Pricing::class, 'edit'])->name('adminEditPricing');
            // Route::get('/get', [Pricing::class, 'get'])->name('getAdminPricing');
            // Route::get('/add/{id}', [Pricing::class, 'add'])->name('adminAddPricing');
            // Route::get('/{id}', [Pricing::class, 'index'])->name('adminPricing');

            Route::get('/edit/{productId}/{pricingId}', [Pricing::class, 'edit'])->name('adminEditPricing');
            Route::get('/add/{id}', [Pricing::class, 'add'])->name('adminAddPricing');
            Route::get('/get', [Pricing::class, 'get'])->name('getAdminPricingData'); // Move this route up
            Route::get('/{id}', [Pricing::class, 'index'])->name('adminPricing');

            
            

            Route::post('/doAdd', [Pricing::class, 'doAdd'])->name('adminDoAddPricing');
            Route::post('/doUpdate', [Pricing::class, 'doUpdate'])->name('adminDoUpdatePricing');
            Route::post('/doDelete', [Pricing::class, 'doDelete'])->name('adminDeletePricing');
            Route::post('/doBulkDelete', [Pricing::class, 'doBulkDelete'])->name('adminBulkDeletePricing');
            Route::post('/getPaperGsm', [Pricing::class, 'getPaperGsm'])->name('getAdminPaperGsm');
            Route::post('/getPaperType', [Pricing::class, 'getPaperType'])->name('getAdminPaperType');
            Route::post('/getPricing', [Pricing::class, 'getPricing'])->name('getAdminPricing');

            
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