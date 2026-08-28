<?php


use App\Http\Controllers\HolidayController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RazorpayController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponUserController;
use App\Http\Controllers\ZoneProcessingController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DeliveryManagementController;
use App\Http\Controllers\NumberToWordsController;
use App\Http\Controllers\ProductRequestController;
use App\Http\Controllers\CustomController;
use App\Http\Controllers\PhonePeController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\OutstandingStatementController;
use App\Http\Controllers\ProController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\DemocategController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\AdminnewController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CategorynewController;
use App\Http\Controllers\SubcategorynewController;
use App\Http\Controllers\ProductnewController;
use App\Http\Controllers\UsernewController;
use App\Http\Controllers\EnquirynewController;
use App\Http\Controllers\festivalandoffersController;
use App\Http\Controllers\PincodeController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LayoutAdminController;
use App\Http\Controllers\LayoutController;
use App\Http\Controllers\BrandImageController;
use App\Http\Controllers\BrandsassocController;
use App\Http\Controllers\ClientserveController;
use App\Http\Controllers\PopupController;
use App\Http\Controllers\SalesInvoiceController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VendorPriceListController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\RackReceivingController;
use App\Http\Controllers\PickListController;
use App\Http\Controllers\LogisticController;
use App\Http\Controllers\DeliveryModeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\VendorOutstandingController;
use App\Http\Controllers\CreditNoteController;
use App\Http\Controllers\DebitNoteController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\ShortMaterialLogController;
use App\Http\Controllers\CustomerSalesReportController;
use App\Http\Controllers\OrderModifyController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ReorderReportController;
use App\Http\Controllers\StockDisposalController;
use App\Http\Controllers\ReturnReportController;
use App\Http\Controllers\PriceChangeLogController;
use App\Http\Controllers\LeadCustomerController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\OutletSelectionController;
use App\Http\Controllers\DashboardAssignmentController;
use App\Http\Controllers\OverdueOutletController;
use App\Http\Controllers\MobilePriceListController;
use App\Http\Controllers\MobileCartController;
use App\Http\Controllers\MobileHomeController;
use App\Http\Controllers\StockReturnController;
use App\Http\Controllers\WarehouseStockReturnController;
use App\Http\Controllers\AccountStatementController;
use App\Http\Controllers\PaymentsOutstandingController;
// use App\Http\Controllers\AdminPanelController;
// Add this for export t excel:
use App\Http\Controllers\ExportController;

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


Route::middleware(['auth:admin'])->group(function () {

    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');

    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');

    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');

    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    Route::get('/roles/{role}/permissions', [RolePermissionController::class, 'edit'])
        ->name('roles.permissions.edit');
    Route::post('/roles/{role}/permissions', [RolePermissionController::class, 'update'])
        ->name('roles.permissions.update');
        
        
    Route::get('dashboard-assignment', [DashboardAssignmentController::class, 'index'])->name('dashboard-assignment.index');
    Route::get('dashboard-assignment/create', [DashboardAssignmentController::class, 'create'])->name('dashboard-assignment.create');
    Route::post('dashboard-assignment', [DashboardAssignmentController::class, 'store'])->name('dashboard-assignment.store');
    Route::get('dashboard-assignment/{id}/edit', [DashboardAssignmentController::class, 'edit'])->name('dashboard-assignment.edit');
    Route::put('dashboard-assignment/{id}', [DashboardAssignmentController::class, 'update'])->name('dashboard-assignment.update');
    Route::delete('dashboard-assignment/{id}', [DashboardAssignmentController::class, 'destroy'])->name('dashboard-assignment.destroy');    
});

Route::middleware(['auth:admin'])->group(function () {

    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');

    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');

    Route::get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');

    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
});



Route::get('/get-header-counts', [LayoutController::class, 'getCounts'])->name('header.counts');
Route::get('/', [HomeController::class, 'landingPage'])->name('landingPage');

// Route::get('/outlet-selection', [HomeController::class, 'OutletSelection'])->name('web.outlet.create');
Route::middleware('auth')->group(function () {
Route::get('/outlet-selection', [OutletSelectionController::class, 'selectOutlet'])->name('web.outlet.select');
Route::get('/manage-outlets', [OutletSelectionController::class, 'manageOutlets'])->name('web.outlet.manage');
Route::get('/outlet-selection/choose/{id}', [OutletSelectionController::class, 'chooseOutlet'])->name('web.outlet.choose');
Route::get('/outlet-selection/create', [OutletSelectionController::class, 'createOutletForm'])->name('web.outlet.create');
Route::post('/outlet-selection/store', [OutletSelectionController::class, 'outletStore'])->name('web.outlet.store');

Route::get('/price-list', [MobilePriceListController::class, 'pricelist'])->name('web.price.list');
Route::get('/assistant/products', [MobilePriceListController::class, 'assistantProducts'])->name('assistant.products');
Route::get('/assistant/cart', [MobilePriceListController::class, 'assistantCart'])->name('assistant.cart');
Route::post('/assistant/cart-snapshot', [MobilePriceListController::class, 'assistantCartSnapshot'])->name('assistant.cart.snapshot');
Route::post('/assistant/cart/{cartId}/quantity', [MobilePriceListController::class, 'assistantCartSetQuantity'])->name('assistant.cart.quantity');
Route::delete('/assistant/cart/{cartId}', [MobilePriceListController::class, 'assistantCartRemove'])->name('assistant.cart.remove');
Route::delete('/assistant/cart', [MobilePriceListController::class, 'assistantCartClear'])->name('assistant.cart.clear');
Route::get('/assistant/history', [MobilePriceListController::class, 'assistantHistory'])->name('assistant.history');
Route::delete('/assistant/history/{conversationId}', [MobilePriceListController::class, 'deleteAssistantConversation'])->name('assistant.history.delete');
Route::get('/assistant/welcome', [MobilePriceListController::class, 'assistantWelcome'])->name('assistant.welcome');
Route::post('/assistant/onboarding-intent', [MobilePriceListController::class, 'assistantOnboardingIntent'])->name('assistant.onboarding-intent');
Route::get('/assistant/previous-orders', [MobilePriceListController::class, 'assistantPreviousOrders'])->name('assistant.previous-orders');
Route::post('/assistant/reorder', [MobilePriceListController::class, 'assistantReorder'])->name('assistant.reorder');
Route::post('/assistant/catalogue-enquiry', [MobilePriceListController::class, 'assistantCatalogueEnquiry'])->name('assistant.catalogue-enquiry');
Route::post('/assistant/checkout-data', [MobilePriceListController::class, 'assistantCheckoutData'])->name('assistant.checkout-data');
Route::post('/assistant/chat', [MobilePriceListController::class, 'assistantChat'])->name('assistant.chat');
Route::post('/assistant/selection', [MobilePriceListController::class, 'assistantSelection'])->name('assistant.selection');
Route::post('/assistant/transcribe', [MobilePriceListController::class, 'assistantTranscribe'])->name('assistant.transcribe');
Route::post('/assistant/speak', [MobilePriceListController::class, 'assistantSpeak'])->name('assistant.speak');
Route::get('/ai-assistant', function () {
    // The assistant is an inline, stateful client. Avoid serving an older
    // browser/WebView copy after a conversational-flow update.
    return response()
        ->view('mobile.ai-assistant')
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
})->name('web.ai.assistant');
Route::get('/chekout', [MobilePriceListController::class, 'chekout'])->name('web.chekout');
Route::get('/order-tracker', [MobilePriceListController::class, 'order_tracker'])->name('web.order.tracker');
Route::post('/favorite/toggle', [MobilePriceListController::class, 'toggle'])->name('favorite.toggle');

Route::post('/cart/add', [MobileCartController::class, 'add'])->name('cart.add');
Route::post('/cart/update-quantity', [MobileCartController::class, 'updateQuantity'])->name('cart.update-quantity');
Route::post('/cart/remove', [MobileCartController::class, 'remove'])->name('cart.remove');

Route::get('/home', [MobileHomeController::class, 'home'])->name('web.home');

Route::get('/account-statement', [AccountStatementController::class, 'index'])->name('web.account-statement');
Route::get('/account-statement/month-details', [AccountStatementController::class, 'monthDetails'])->name('web.account-statement.month-details');
Route::get('/account-statement/download', [AccountStatementController::class, 'download'])->name('web.account-statement.download');

Route::get('/payments-outstanding', [PaymentsOutstandingController::class, 'index'])->name('web.payments-outstanding');
Route::get('/payments-outstanding/month-invoices', [PaymentsOutstandingController::class, 'monthInvoices'])->name('web.payments-outstanding.month-invoices');


});



// admin panel
Route::get('dashboard', [AdminController::class, 'dashboard']);
Route::get('notification/read/{id}', [AdminController::class, 'notificationRead'])->name('notification.read');
Route::get('usernotification/read/{id}', [AdminController::class, 'usernotificationRead'])->name('usernotification.read');
Route::get('ordernotification/read/{id}', [AdminController::class, 'ordernotificationRead'])->name('ordernotification.read');

Route::resource('subcategories', SubcategoryController::class);
// Route::resource('products', ProductController::class);
Route::post('products/import', [ProductController::class, 'productImportFiles'])->name('products.import');

//brand category
Route::resource('brands', BrandController::class);
Route::post('brands/store', [BrandController::class, 'store'])->name('brands.store');
Route::get('/brands/{id}/edit', [BrandController::class, 'edit'])->name('brands.edit');
Route::put('/brands/{id}', [BrandController::class, 'update'])->name('brands.update');
Route::delete('delete-brands/{id}', [BrandController::class, 'destroy']);

//type category
Route::resource('types', TypeController::class);
Route::post('type/store', [TypeController::class, 'store'])->name('type.store');
Route::get('/type/{id}/edit', [TypeController::class, 'edit'])->name('type.edit');
Route::put('/type/{id}', [TypeController::class, 'update'])->name('type.update');
Route::delete('delete-type/{id}', [TypeController::class, 'destroy']);

//tag category
Route::resource('tags', TagController::class);
Route::get('/tag/{id}/edit', [TagController::class, 'edit'])->name('tag.edit');
Route::put('/tag/{id}', [TagController::class, 'update'])->name('tag.update');
Route::delete('delete-tag/{id}', [TagController::class, 'destroy']);

// categories
Route::resource('categories', CategoryController::class);
Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
Route::get('privacy_policy', [CategoryController::class, 'privacy_policy'])->name('privacy_policy');
Route::get('T&C', [CategoryController::class, 'terms_conditions'])->name('T&C');
Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('delete-categories/{id}', [CategoryController::class, 'destroy']);
Route::post('/home/updateNotification', [HomeController::class, 'updateNotification']);
// subcategories
Route::put('/subcategories/{id}', [SubcategoryController::class, 'update'])->name('subcategories.update');
Route::delete('delete-sub/{id}', [SubcategoryController::class, 'destroy']);

// products
Route::delete('delete-productss/{id}', [ProductController::class, 'destroy']);

//admin login
Route::get('admin', [AdminController::class, 'index']);
Route::post('admin/auth', [AdminController::class, 'auth'])->name('admin.auth');

//admin add user
Route::resource('user', UserController::class);

Route::post('/update-user-info', [UserController::class, 'updateUserInfo'])->name('update-user-info');
Route::post('/notifications/update', [LayoutAdminController::class, 'update'])->name('notifications.update');
Route::post('/usernotifications/update', [LayoutAdminController::class, 'userupdate'])->name('newusernotifications.update');


Route::post('admin/store', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::get('/userslist/index', [UserController::class, 'userlist'])->name('users.list.indexx');
Route::put('/admin/{id}', [UserController::class, 'update'])->name('admin.update');
Route::delete('delete-users/{id}', [UserController::class, 'destroy']);
Route::get('admin/logout', [AdminController::class, 'logout'])->name('admin.logout');
// Route::get('/admin/notifications', [AdminPanelController::class, 'showNotifications'])->name('admin.notifications');
// Route::put('/admin/notifications/{notification}', [AdminPanelController::class, 'markNotificationAsRead'])->name('admin.mark_notification_as_read');

// subcategory/brand category product/example
Route::get('/cats', [ProController::class, 'index'])->name('categoriess.index');
Route::get('/democats/{categoryId}/products', [ProController::class, 'getCategoryProducts'])->name('democats.index');;
Route::get('/cats/{categoryId}/products', [ProController::class, 'getCategoryProducts']);
Route::get('/subcats/{categoryId}/products', [ProController::class, 'getSubcategoryProducts'])->name('subcats.index');
Route::get('/brandcates/{brandCategoryId}/products', [ProController::class, 'getBrandCategoryProducts']);

//democategoriesproduct
Route::get('/categsub', [DemocategController::class, 'procat']);
Route::get('/brandscateg', [DemocategController::class, 'getBrands']);
Route::get('/filter-products-categ', [DemocategController::class, 'filterProducts']);
Route::get('brandscateg/{id}', [DemocategController::class, 'category']);
Route::get('/import-users', [UserController::class, 'importUsers'])->name('import');
Route::get('export/', [UserController::class, 'export'])->name('export');
Route::post('/upload-users', [UserController::class, 'uploadUsers'])->name('users.upload');
Route::post('/upload-products', [UserController::class, 'uploadProducts'])->name('users.uploadproducts');
Route::get('export/', [UserController::class, 'export'])->name('users.export');
Route::get('exportproduct/', [UserController::class, 'exportproduct'])->name('products.export');

// routes/web.php subcategory and their products on same page
Route::get('/categoriess', [CustomController::class, 'categoriess'])->name('categoriess');
Route::get('/home1', [CustomController::class, 'index'])->name('home1');
Route::get('/categories/{category}/subcategories', [CustomController::class, 'indexx'])->name('home.index');
Route::get('/subcategories_lists/{subcategory}/products_lists', [CustomController::class, 'products'])->name('home.products');
Route::get('/subcat/products/filter', [CustomController::class, 'filterProducts'])->name('home.filterProducts');
Route::get('/subcat/products/filtertype', [CustomController::class, 'filterTypeProducts'])->name('home.filterTypeProducts');
Route::get('/subcat/products/tag/filter', [CustomController::class, 'filterTagProducts'])->name('home.filterTagProducts');

// otp login
Route::post('/customer/sendOtp', [CustomerAuthController::class, 'sendOtp'])->name('customer.sendOtp');
Route::post('/customer/verifyOtp', [CustomerAuthController::class, 'verifyOtp'])->name('customer.verifyOtp');
Route::post('/customer/login', [CustomerAuthController::class, 'login'])->name('customer.login');
Route::post('/customer/validate-email-mobile', [CustomerAuthController::class, 'validateemailmobile'])->name('customer.validate-email-mobile');
Route::post('/customer/reset-password', [CustomerAuthController::class, 'resetpassword'])->name('customer.reset-password');
Route::post('/customer/verifyloginOtp', [CustomerAuthController::class, 'verifyloginOtp'])->name('customer.verifyloginOtp');
Route::get('/logout', [CustomerAuthController::class, 'destroy'])->name('customer.logout');

Route::get('/customer/name/{number}', [CustomerAuthController::class, 'checkName'])->name('customer.name');
Route::post('/customer/check-email', [CustomerAuthController::class, 'checkEmail']);

Route::post('/outlet/store', [CustomerAuthController::class, 'outletstore'])->name('store.outlet');
Route::get('verify-outlet/{id}', [OutletController::class, 'verifyOutlet'])->name('verifyOutlet');
Route::get('delete-outlet/{id}', [OutletController::class, 'deleteOutlet'])->name('deleteOutlet');
Route::post('submit-outlet-documents', [OutletController::class, 'submitOutletDocuments'])->name('submitoutletDocuments');
Route::post('/update_shipping_address', [OutletController::class, 'update_outlet'])->name('update_shipping_address');
Route::post('/admin/outlet/save', [OutletController::class, 'saveOutlet'])->name('outlet.save');
Route::put('/update-outlet/{id}', [OutletController::class, 'update_save'])->name('update.outlet');
//checkout
Route::get('checkout/{id}', [CheckoutController::class, 'index'])->name('checkout');



// user has customer
Route::get('/customer/product/details/{user}', [CustomerAuthController::class, 'productShow'])->name('customer.product.details');
Route::get('/customer/notifications', [CustomerAuthController::class, 'customerNotifications'])->name('customer.notification.details');
Route::get('/order/notifications', [CustomerAuthController::class, 'orderNotifications'])->name('order.notification.details');
Route::get('/admin/notifications', [CustomerAuthController::class, 'adminNotifications'])->name('admin.notification.details');
Route::delete('delete-customer/{id}', [CustomerAuthController::class, 'delete']);

//singleproduct page
Route::get('product/{product}', [HomeController::class, 'product'])->name('product-details');

//new
Route::post('/enquiry/store', [EnquiryController::class, 'store'])->name('enquiry.store');
Route::get('/enquiry/edit/{enquiry}', [EnquiryController::class, 'edit'])->name('enquiry.edit');
Route::post('/enquiry/update/{enquiry}', [EnquiryController::class, 'update'])->name('enquiry.update');
Route::post('offer/request/{enquiry}', [EnquiryController::class, 'offerRequest'])->name('offer.request');
Route::post('offer/reject/{enquiry}', [EnquiryController::class, 'offerReject'])->name('offer.reject');
Route::post('offer/reoffer/{enquiry}', [EnquiryController::class, 'offerreoffer'])->name('offer.reoffer');

Route::post('/send-last-reoffer-notification', [EnquiryController::class, 'sendLastReofferNotification']);


Route::post('offer/reoffer1/{enquiry}', [EnquiryController::class, 'offerreoffer1'])->name('offer.reoffer1');
Route::post('offer/delete/{enquiry}', [EnquiryController::class, 'destroy'])->name('offer.remove');
Route::post('customer-price/delete/{customerPrice}', [EnquiryController::class, 'destroyCustomerPrice'])->name('customerprice.remove');
Route::post('/enquiry/store/singleproduct', [EnquiryController::class, 'addSingleProduct'])->name('enquiry.store.singleproduct');
Route::get('enquiry/export/', [EnquiryController::class, 'exportData'])->name('enquiry.export');
Route::post('enquiry/import/', [EnquiryController::class, 'importData'])->name('enquiry.import');
Route::get('/mark-as-read', [EnquiryController::class, 'markAsRead'])->name('mark-as-read');
Route::get('/status/update/{status}', [EnquiryController::class, 'statusUpdate'])->name('status.update');
Route::post('/admin/enquiry/offerPrice', [EnquiryController::class, 'offerPriceUpdate'])->name('admin.enquiry.offerPrice.store');
Route::post('/admin/enquiry/rejected-list', [EnquiryController::class, 'comment'])->name('admin.enquiry.comments.store');
Route::get('/status/report/{status}', [EnquiryController::class, 'statusChanges'])->name('status.view');
Route::get('/enquiries/{id}', [EnquiryController::class, 'showEnquiry'])->name('enquiries.show');
Route::post('cart/create', [CartController::class, 'create'])->name('cart.create');
Route::post('cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::get('cart/update/quantity', [CartController::class, 'updateQty'])->name('cart.update.qty');
Route::get('cart/increase/quantity', [CartController::class, 'quantityMinus'])->name('cart.increase.qty');
Route::get('cart/view/table', [CartController::class, 'cartVagetpincodelue'])->name('cart.cartValue');
Route::get('subcategory/pages', [HomeController::class, 'subcategoryPages'])->name('home.subcategoryPages');
Route::get('subcategory/redirect/catalogue/page/{subcategory}', [HomeController::class, 'redirectAntherPage'])->name('subcategory.redirect.catalogue.page');
Route::get('search', [HomeController::class, 'search'])->name('search.name');
Route::get('search/{search}', [HomeController::class, 'searchdata'])->name('search.data');

Route::get('/categories/{category}/subcategories', [CustomAuthController::class, 'indexx'])->name('home.index');
Route::get('/subcategories/{subcategory}/products', [CustomAuthController::class, 'products'])->name('home.products');

//New Admin Panel theme
Route::get('adminnew', [AdminnewController::class, 'index']);
Route::post('admin/newauth', [AdminnewController::class, 'auth'])->name('admin.newauth');
Route::get('admin/newlogout', [AdminnewController::class, 'logout'])->name('admin.newlogout');
Route::get('dashboardd', [AdminnewController::class, 'dashboard'])->name('dashboard.index');

// before login page
Route::get('index', [HomeController::class, 'index']);
Route::get('homepage', [HomeController::class, 'home'])->name('homepage');

Route::post('/popup-track', [PopupController::class, 'track']);

Route::get('/subcateg/{id?}', [HomeController::class, 'subcateg'])->name('subcateg');
// Route::get('/subcat/products/filtertype', [HomeController::class, 'filterTypeProducts'])->name('subcateg.filterTypeProducts');
Route::get('/subcateg', [HomeController::class, 'subcateg'])->name('subcateg');
Route::get('requestproduct', [HomeController::class, 'requestproduct'])->name('requestproduct');
Route::get('productlist', [HomeController::class, 'productlist'])->name('productlist');
Route::get('orders', [HomeController::class, 'orders'])->name('orders');
Route::post('orders_filter', [HomeController::class, 'orders_filter'])->name('orders_filter');
Route::get('fetch-orders', [HomeController::class, 'fetch_orders'])->name('fetch-orders');
Route::get('profile', [HomeController::class, 'profile'])->name('profile');

Route::post('/profile/update-image', [HomeController::class, 'updateImage'])->name('profile.updateImage');

// new categories
Route::resource('categoriess', CategorynewController::class);
Route::get('/categoriess/{id}/edit', [CategorynewController::class, 'edit'])->name('categoriess.edit');
Route::put('/categoriess/{id}', [CategorynewController::class, 'update'])->name('categoriess.update');
Route::delete('delete-categoriess/{id}', [CategorynewController::class, 'destroy']);

//new subcategories
Route::resource('subcategoriess', SubcategorynewController::class);
Route::put('/subcategoriess/{id}', [SubcategorynewController::class, 'update'])->name('subcategoriess.update');
Route::delete('delete-subcategoriess/{id}', [SubcategorynewController::class, 'destroy']);

//new product
Route::resource('productss', ProductnewController::class);
Route::put('/productss/{product}', [ProductnewController::class, 'update'])->name('productss.update');
Route::get('productss', [ProductnewController::class, 'index'])->name('productss.index');
Route::post('productss/import', [ProductnewController::class, 'productImportFiles'])->name('productss.import');
Route::get('newexportproduct/', [UsernewController::class, 'newexportproduct'])->name('productss.export');
Route::get('newexportorder/', [UsernewController::class, 'newexportorder'])->name('orders.export');
Route::delete('/products/{id}', [ProductnewController::class, 'destroy']);
Route::get('approved-export/', [EnquirynewController::class, 'approvedExport'])->name('aprroved_list.export');

//users list
Route::resource('users', UsernewController::class);
Route::get('/users/{id}/edit', [UsernewController::class, 'edit'])->name('usersedit.edit');
Route::put('/admin/{id}', [UsernewController::class, 'update'])->name('usersupdate.update');
Route::delete('delete-userss/{id}', [UsernewController::class, 'destroy']);

//notification list
Route::get('/customer/notificationss', [CustomerAuthController::class, 'customerNotificationss'])->name('customer.notification.detailss');
Route::get('/order/notificationss', [CustomerAuthController::class, 'orderNotificationss'])->name('order.notification.detailss');
Route::get('/admin/notificationss', [CustomerAuthController::class, 'adminNotificationss'])->name('admin.notification.detailss');
Route::get('/customer/product/detailss/{user}', [CustomerAuthController::class, 'productShowdata'])->name('customer.product.detailss');
Route::get('/customer/product/detailss/new/{user}', [CustomerAuthController::class, 'productShowdatanew'])->name('customer.product.detailss.new');
Route::get('/customer/indexx', [CustomerAuthController::class, 'indexx'])->name('customer.indexx');
Route::get('/customer/indexx1', [CustomerAuthController::class, 'indexx1'])->name('customer.indexx1');
Route::get('/customer/best_customer', [CustomerAuthController::class, 'BestCustomer'])->name('customer.best_customer');
Route::put('/update-customer/{id}', [CustomerAuthController::class, 'update'])->name('update-customer');
Route::get('/edit-customer/{id}', [CustomerAuthController::class, 'edit'])->name('edit-customer');
Route::get('/outletadd-customer/{id}', [CustomerAuthController::class, 'outletadd'])->name('outletadd-customer');
Route::get('/edit-outlet/{id}', [CustomerAuthController::class, 'edit_outlet'])->name('edit.outlet');

Route::get('customers/export-outlets', [CustomerAuthController::class, 'exportOutlets'])->name('customer.export.outlets');



Route::get('/customer/create', [CustomerAuthController::class, 'create'])->name('customer.create');
Route::post('/customer/save', [CustomerAuthController::class, 'Savecustomer'])->name('customer.save');
Route::get('/customer/{id}/edit', [CustomerAuthController::class, 'customer_edit'])->name('customer.edit');
Route::post('/customer/{id}/update', [CustomerAuthController::class, 'customer_update'])->name('customer.update');



// Route::delete('/delete-enquiry/{id}', [CustomerAuthController::class, 'deleteEnquiry'])->name('enquiry.delete');
Route::match(['post', 'delete'], '/delete-enquiry/{id}', [EnquiryController::class, 'destroy_enquiry'])->name('enquiry.destroy');



//enquiry list
Route::get('/enquiry/indexx', [EnquirynewController::class, 'index'])->name('enquiry.indexx');
Route::get('/approved/list', [EnquirynewController::class, 'approved'])->name('approved.list');
Route::get('/approved/customer', [EnquirynewController::class, 'approved_customer'])->name('approved.customer');
Route::get('/status/report/{status}', [EnquirynewController::class, 'statusChanges'])->name('enquirystatus.view');
Route::get('/submitted/view/all', [EnquirynewController::class, 'submittedviewAll'])->name('submitted.all');
Route::get('/submitted/view/{status}', [EnquirynewController::class, 'submittedview'])->name('submitted.view');

Route::get('/enquiry/editt/{enquiry}', [EnquirynewController::class, 'editt'])->name('enquiryy.edit');
Route::post('/enquiry/updatestatus/{enquiry}', [EnquirynewController::class, 'updatestatus'])->name('enquiry.updatestatus');

//Outstanding Details
Route::get('/outstanding/details', [OutstandingStatementController::class, 'index'])->name('outstanding.details');

Route::get('/vendor/outstanding', [VendorOutstandingController::class, 'index'])
    ->name('vendor.outstanding.index');
Route::get('/vendor/outstanding/{vendor}', 
    [VendorOutstandingController::class, 'details']
)->name('vendor.outstanding.details');
Route::get(
    '/vendor/outstanding/pdf/{vendor}',
    [VendorOutstandingController::class, 'vendorOutstandingPdf']
)->name('vendor.outstanding.pdf');

//ReturnReportController
Route::get('admin/return-report', [ReturnReportController::class, 'index'])->name('admin.return.report');
Route::get('admin/debit-note/download/{id}', [ReturnReportController::class, 'downloadSingle'])->name('admin.debit-note.download.single');

//PriceChangeLogController
Route::post('/price-log/update/{id}', [PriceChangeLogController::class, 'updateIndividual'])->name('admin.price.logs.update.individual');
Route::get('admin/price-change-logs', [PriceChangeLogController::class, 'index']) ->name('admin.price.logs');
Route::post('admin/price-change-logs/{id}/approve-flat', [PriceChangeLogController::class, 'approveFlat'])->name('admin.price.logs.approve.flat');
Route::post('admin/price-change-logs/{id}/reject', [PriceChangeLogController::class, 'reject'])->name('admin.price.logs.reject');
Route::get('admin/price-change-logs/{id}/edit', [PriceChangeLogController::class, 'edit'])->name('admin.price.logs.edit');
Route::post('admin/price-change-logs/{id}/update-individual', [PriceChangeLogController::class, 'updateIndividual'])->name('admin.price.logs.update.individual');
Route::post('admin/price-change-logs/{id}/update-individual',[PriceChangeLogController::class, 'updateIndividual'])->name('admin.price.logs.update.individual');



//StockDisposal
Route::get('admin/disposals', [StockDisposalController::class, 'index'])->name('admin.disposals.index');
Route::post('admin/disposals', [StockDisposalController::class, 'store'])->name('admin.disposals.store');
Route::post('/disposals/bulk-opening', [StockDisposalController::class, 'bulkOpeningDispose'])->name('admin.disposals.bulkOpeningDispose');
Route::get('/stock-damaged/create', [StockDisposalController::class, 'create'])->name('stock-damaged.create');
Route::post('/stock-damaged/store', [StockDisposalController::class, 'placed'])->name('stock-damaged.store');
Route::get('/get-product-stock', [StockDisposalController::class, 'getProductStock']);


//ReorderReportController
Route::get('/admin/reorder-report', [ReorderReportController::class,'index'])->name('admin.reorder.report');
Route::get('/admin/reorder-qty-report', [ReorderReportController::class,'reorderQty'])->name('admin.reorder.qty.report');
Route::get('/admin/reorder-qty-report-point', [ReorderReportController::class,'ReorderQtyPoint'])->name('admin.reorder.qty.report.point');
Route::post('/admin/save-scheme', [ReorderReportController::class, 'saveScheme'])->name('admin.scheme.save');

Route::get('admin/reports/overdue-details/{type}', [AdminnewController::class, 'overdueDetailsReport'])
    ->name('admin.reports.overdue-details');

Route::post('admin/reports/overdue-followup/save', [AdminnewController::class, 'saveOverdueFollowup'])
    ->name('admin.reports.overdue-followup.save');
    
    
Route::get('admin/reports/inventory-details/{type}', [AdminnewController::class, 'inventoryDetailsReport'])
    ->name('admin.reports.inventory-details');
    
Route::get('admin/reports/inventory-details/{type}/export', [AdminnewController::class, 'inventoryDetailsExport'])
    ->name('admin.reports.inventory-details.export');        
    
Route::get('admin/reports/overdue-details/outlet/{id}', [AdminnewController::class, 'overdueOutletDetail'])
    ->name('admin.reports.overdue-outlet-detail'); 
    
    // For Sales Report Open That Page Today, Privious and Month
    
Route::get('/admin/sales/today', [AdminnewController::class, 'todaySales'])
    ->name('admin.sales.today');

Route::get('/admin/sales/yesterday', [AdminnewController::class, 'previousDaySales'])
    ->name('admin.sales.previous');

Route::get('/admin/sales/month', [AdminnewController::class, 'thisMonthSales'])
    ->name('admin.sales.month');

Route::get('admin/reports/overdue-outlet/{id}', [OverdueOutletController::class, 'overdueOutletDetail'])
    ->name('admin.reports.overdue-outlet-detail');

Route::get('admin/reports/overdue-outlet/{id}/pdf', [OverdueOutletController::class, 'overdueOutletDetailPdf'])
    ->name('admin.reports.overdue-outlet-detail.pdf');

Route::get('admin/reports/overdue-outlet/{id}/excel', [OverdueOutletController::class, 'overdueOutletDetailExcel'])
    ->name('admin.reports.overdue-outlet-detail.excel');      

Route::post('/save-lss', [ReorderReportController::class, 'saveLss'])->name('admin.save.lss');
Route::post('/save-reorder-setting', [ReorderReportController::class, 'saveReorderSetting'])->name('admin.save.reorder.setting');   

Route::get('/admin/nonRunningProductsReport', [ReorderReportController::class,'nonRunningProductsReport'])->name('admin.nonRunningProductsReport');

//ReportsController
Route::get('/admin/near-expiry-stock', [ReportsController::class, 'nearExpiryStock'])->name('admin.near-expiry-stock');
Route::get('/admin/expired-products', [ReportsController::class, 'expiredStock'])->name('admin.expired-products');
Route::post('/admin/put-on-sale', [ReportsController::class, 'putOnSale'])->name('admin.put-on-sale');
Route::post('/remove-from-sale', [ReportsController::class, 'removeFromSale'])->name('admin.remove-from-sale');
Route::get('/admin/urgent-sale-stock', [ReportsController::class, 'urgentSaleStock'])->name('admin.urgent-sale-stock');
Route::post('/toggle-pick-list', [ReportsController::class, 'togglePickList'])->name('admin.toggle-pick-list');    

// delivery management add export to excel: 
Route::get('/delivery/export', [DeliveryManagementController::class, 'exportDelivery'])->name('export.delivery');

// enquiry worksheet add export to excel: 
Route::get('/export-enquiry', [ExportController::class, 'exportEnquiry'])->name('export.enquiry');

//Order Details

Route::get('/order/details', [OrderController::class, 'index'])->name('order.details');
Route::get('/order/backend-details', [OrderController::class, 'backendIndex'])->name('order.backend.details');
Route::get('/order/details_ujala', [OrderController::class, 'ujala'])->name('order.ujala');
Route::get('/order/details/{id}', [OrderController::class, 'indexID'])->name('order.detailsid');
Route::get('/order/detailss/{id}', [OrderController::class, 'indexName'])->name('order.detailsname');
Route::post('/order/cancel/{id}', [OrderController::class, 'cancel_Order'])->name('order.accept.cancel');

Route::get('order/docs/{id}', [OrderController::class, 'docsView']);

Route::get('/order/edit/{id}', [OrderController::class, 'edit'])->name('order.edit');
Route::put('/order/{id}', [OrderController::class, 'update'])->name('order.update');

//Order Item
Route::get('/order/modify/{id}', 
    [OrderModifyController::class, 'index']
)->name('order.modify');
Route::post('/order/modify/update', [OrderModifyController::class, 'update'])
    ->name('order.modify.update');
Route::get('/orderitem/details/{id}', [OrderItemController::class, 'index'])->name('orderitem.details');
Route::put('/orderItems/{id}', [OrderItemController:: class, 'update'])->name('update.orderItem');
Route::post('/order-item/update-quantity', [OrderItemController::class, 'updateQuantity'])->name('orderItem.updateQuantity');
// Route::get('/orderitem/details/{id}', [OrderController::class, 'indexID'])->name('orderitem.detailsid');
Route::get('/orderitem/edit/{id}', [OrderController::class, 'edit'])->name('orderitem.edit');
Route::put('/orderitem/{id}', [OrderController::class, 'update'])->name('orderitem.update');
Route::delete('/delete-order-item/{id}', [OrderItemController::class, 'destroy'])->name('orderitem.delete');;


//inward stock and update invoice
Route::get('admin/stock-return/create', [StockReturnController::class, 'create'])->name('stock-return.create');
Route::get('admin/stock-return/search-orders', [StockReturnController::class, 'searchOrders'])->name('stock-return.search-orders');
Route::get('admin/stock-return/order-items/{order}', [StockReturnController::class, 'getOrderItems']);
Route::post('admin/stock-return/store', [StockReturnController::class, 'store'])->name('stock-return.store');

Route::get('admin/warehouse/stock-return', [WarehouseStockReturnController::class, 'index'])->name('warehouse.stock-return.index');
Route::post('admin/warehouse/stock-return/{id}/approve', [WarehouseStockReturnController::class, 'approve']);
Route::post('admin/warehouse/stock-return/{id}/reject', [WarehouseStockReturnController::class, 'reject']);


Route::get('admin/stock-return', [StockReturnController::class, 'index'])->name('stock-return.index');
Route::get('admin/stock-return/{id}', [StockReturnController::class, 'show'])->name('stock-return.show');
Route::get('admin/stock-return/{id}/edit', [StockReturnController::class, 'edit'])->name('stock-return.edit');
Route::put('admin/stock-return/{id}', [StockReturnController::class, 'update'])->name('stock-return.update');

Route::get('admin/warehouse/stock-return/{id}', [WarehouseStockReturnController::class, 'show'])->name('warehouse.stock-return.show');

Route::get('admin/stock-return/products-by-customer/{customerId}/{outletId}', [StockReturnController::class, 'getProductsByCustomer'])
    ->name('stock-return.products-by-customer');




//banners
Route::get('banners', [BannerController::class, 'index'])->name('banners.index');
Route::delete('delete-banner/{id}', [BannerController::class, 'destroy'])->name('banners.destroy');
Route::post('banners', [BannerController::class, 'store'])->name('banners.store');
Route::get('banners/create', [BannerController::class, 'create'])->name('banners.create');
Route::get('banners/{id}/edit', [BannerController::class, 'edit'])->name('banners.edit');
Route::put('banners/{id}', [BannerController::class, 'update'])->name('banners.update');

//Pincode
Route::get('pincode/createNew', [PincodeController::class, 'createNew'])->name('pincode.createNew');

Route::get('pincode/{id}', [PincodeController::class, 'index'])->name('pincode.index');
Route::delete('delete-pincode/{id}', [PincodeController::class, 'destroy']);
Route::post('pincode', [PincodeController::class, 'store'])->name('pincode.store');

Route::get('pincode/{id}/edit', [PincodeController::class, 'edit'])->name('pincode.edit');
Route::put('pincode/{id}', [PincodeController::class, 'update'])->name('pincode.update');
Route::put('pincode/{id}/status', [PincodeController::class, 'statusUpdate'])->name('pincode.statusUpdate');
Route::get('/getpincode', [PincodeController::class, 'getPincode'])->name('getPincode');
Route::get('exportpincode', [PincodeController::class, 'exportpincode'])->name('pincode.export');
Route::post('pincode/import', [PincodeController::class, 'pincodeImportFiles'])->name('pincode.import');
Route::get('/pincode/{pincode}', [PincodeController::class, 'checkPincode'])->name('pincode.pincode');

//Quotes New Controllers
Route::post('/quotes/add', [QuoteController::class, 'addToQuote'])->name('quotes.add');
Route::post('/quotes/submit', [QuoteController::class, 'submitQuote'])->name('quotes.submit');
Route::post('/quotes/list', [QuoteController::class, 'addToQuotelist'])->name('quotes.quotelist');
Route::get('/request/quotes', [QuoteController::class, 'quote'])->name('quotes12');
Route::get('/quoteslist', [QuoteController::class, 'quotelist'])->name('quoteslist');
Route::get('/quoteslist_demo', [QuoteController::class, 'quotelist_demo'])->name('quoteslist_demo');
Route::get('delete-quote/{id}', [QuoteController::class, 'removequote'])->name('removequote');
Route::get('/quotes/count', [QuoteController::class, 'getQuoteCount'])->name('quotes.count');
Route::post('/update-accept-cost', [QuoteController::class, 'updateAcceptCost'])->name('update.accept.cost');

//Payment
Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
Route::get('update_payments', [PaymentController::class, 'update_payments'])->name('payments.update_payments');
Route::post('payments/export', [PaymentController::class, 'downloadExcel'])->name('exportaccount.excel');
Route::get('orders/{order}/payment/history', [PaymentController::class, 'history'])->name('payments.history');


//BrandsImage
Route::get('brandsimage', [BrandImageController::class, 'index'])->name('brandsimage.index');
Route::delete('delete-brandsimage/{id}', [BrandImageController::class, 'destroy']);
Route::post('brandsimage', [BrandImageController::class, 'store'])->name('brandsimage.store');
Route::get('brandsimage/create', [BrandImageController::class, 'create'])->name('brandsimage.create');
Route::get('brandsimage/{id}/edit', [BrandImageController::class, 'edit'])->name('brandsimage.edit');
Route::put('brandsimage/{id}', [BrandImageController::class, 'update'])->name('brandsimage.update');
Route::get('brandsimage/{festivalandoffersid}', [BrandImageController::class, 'listing'])->name('brandsimage.listing');
Route::put('brandsimage/{id}/status-update', [BrandImageController::class, 'statusUpdate'])->name('brandsimage.statusUpdate');

//banners
Route::get('festivalandoffers', [FestivalandOffersController::class, 'index'])->name('festivalandoffers.index');
Route::delete('delete-festivalandoffers/{id}', [FestivalandOffersController::class, 'destroy']);
Route::post('festivalandoffers', [FestivalandOffersController::class, 'store'])->name('festivalandoffers.store');
Route::get('festivalandoffers/create', [FestivalandOffersController::class, 'create'])->name('festivalandoffers.create');
Route::get('festivalandoffers/{id}/edit', [FestivalandOffersController::class, 'edit'])->name('festivalandoffers.edit');
Route::put('festivalandoffers/{id}', [FestivalandOffersController::class, 'update'])->name('festivalandoffers.update');
Route::put('festivalandoffers/{id}/status', [FestivalandOffersController::class, 'statusUpdate'])->name('festivalandoffers.statusUpdate');


//Zone Processing

Route::get('/zoneprocessings', [ZoneProcessingController::class, 'index'])->name('zoneprocessings.index');
Route::get('/zoneprocessings/create', [ZoneProcessingController::class, 'create'])->name('zoneprocessings.create');
Route::post('/zoneprocessing', [ZoneProcessingController::class, 'store'])->name('zoneprocessing.store');
Route::put('zoneprocessings/{id}/status', [ZoneProcessingController::class, 'statusUpdate'])->name('zoneprocessing.statusUpdate');
Route::delete('delete-zoneprocessing/{id}', [ZoneProcessingController::class, 'destroy']);
Route::get('zoneprocessing/{id}/edit', [ZoneProcessingController::class, 'edit'])->name('zoneprocessing.edit');
Route::put('zoneprocessing/{id}', [ZoneProcessingController::class, 'update'])->name('zoneprocessing.update');


// Holiday Manage
Route::get('holidays', [HolidayController::class, 'index'])->name('holidays.index');
Route::get('holidays/create', [HolidayController::class, 'create'])->name('holidays.create');
Route::post('holidays', [HolidayController::class, 'store'])->name('holidays.store');
Route::get('holidays/{holiday}/edit', [HolidayController::class, 'edit'])->name('holidays.edit');
Route::put('holidays/{holiday}', [HolidayController::class, 'update'])->name('holidays.update');
Route::delete('holidays/{holiday}', [HolidayController::class, 'destroy'])->name('holidays.destroy');
Route::post('holidays/import', [HolidayController::class, 'import'])->name('holidays.import');
Route::get('holidays/export', [HolidayController::class, 'export'])->name('holidays.export');


//Order Controller
Route::post('/insert-order', [OrderController::class, 'insertOrder'])->name('insert-order');

Route::get('phonepe/{totalDiscountValue}', [PhonePeController::class, 'phonePe']);
Route::get('/delay-redirect', [PhonePeController::class, 'delayRedirect'])->name('delayRedirect');

Route::any('phonepe-response',[PhonePeController::class,'response'])->name('response');
Route::any('phonepe-confirm',[PhonePeController::class,'confirm'])->name('phonepe-confirm');

Route::get('/pay-page', [PhonePeController::class, 'index']);
//++++++++++++++++++++++++++++++++++++++++++++++
//The Payment Route For The Product
//+++++++++++++++++++++++++++++++++++++++++++++
Route::any('pay', [PhonePeController::class, 'payment_init']);
Route::get('pay-refund-view', [PhonePeController::class, 'refund']);
Route::get('pay-refund', [PhonePeController::class, 'payment_refund']);
Route::any('pay-return-url', [PhonePeController::class, 'payment_return'])->name('pay-return-url');
Route::post('pay-callback-url', [PhonePeController::class, 'payment_callback'])->name('pay-callback-url');
Route::any('pay-refund-callback', [PhonePeController::class, 'payment_refund_callback'])->name('pay-refund-callback');

//order-invoice
Route::get('/orderinvoice/{id}', [OrderItemController::class, 'invoice'])->name('orderinvoice.details');
Route::get('/order/invoice/{id}', [OrderController::class, 'invoiceID'])->name('order.invoiceid');
Route::get('/over_due/invoice/{id}', [OrderController::class, 'over_due'])->name('over_due.invoice');

// delivery charges details
Route::get('/orderdeliverycharges/{id}', [OrderItemController::class, 'deliverycharges'])->name('deliverychargesdetails.details');
Route::get('/order/invoice-list/', [OrderController::class, 'invoiceList'])->name('invoice.list');
Route::get('/order/generateInvoiceAndDeliveryCharges/{id}', [OrderItemController::class, 'generateInvoiceAndDeliveryCharges'])->name('generateInvoiceAndDeliveryCharges.list');
Route::get('/iZonik/{id}', [OrderItemController::class, 'generateInvoiceAndDeliveryCharges'])->name('generateInvoiceAndDeliveryCharges.list');

Route::get('/admin/credit-note/generate/{id}', [CreditNoteController::class, 'generateCreditNote'])
    ->name('admin.creditnote.generate');
        Route::get('credit-note', [CreditNoteController::class, 'index'])
    ->name('creditnote.index');
Route::get('credit-note/{id}/download', [CreditNoteController::class, 'download'])
    ->name('creditnote.download');
Route::get('/admin/credit-note/generate/{id}', [CreditNoteController::class, 'generateCreditNote'])
    ->name('admin.creditnote.generate');
    Route::get('/admin/credit-note/create/{id}', [CreditNoteController::class,'create'])
    ->name('creditnote.create');
Route::post('/admin/credit-note/store/{id}', [CreditNoteController::class,'store'])
    ->name('creditnote.store');

   Route::get('/admin/credit-note/download/{id}',
    [CreditNoteController::class,'download'])
    ->name('creditnote.download');    
    


Route::get('debit-note', [DebitNoteController::class, 'index'])
    ->name('debitnote.index');

Route::get('debit-note/create/{id}', [DebitNoteController::class, 'create'])
    ->name('debitnote.create');

Route::post('debit-note/store/{id}', [DebitNoteController::class, 'store'])
    ->name('debitnote.store');

Route::get('debit-note/download/{id}', [DebitNoteController::class, 'download'])
    ->name('debitnote.download');


Route::get('admin/debit-note/generate/{id}', 
    [DebitNoteController::class, 'generateDebitNote'])
    ->name('admin.debitnote.generate');
    
    Route::get('admin/debit-note/from-expiry', 
    [DebitNoteController::class, 'createFromExpiry'])
    ->name('admin.debitnote.from.expiry');
    Route::post('admin/debit-note/store-from-expiry', 
    [DebitNoteController::class, 'storeFromExpiry'])
    ->name('admin.debitnote.store.from.expiry');
    
    Route::get('admin/debitnote/from-expired', [DebitNoteController::class, 'createFromExpired'])
    ->name('admin.debitnote.from.expired');

Route::post('admin/debitnote/store-from-expired', [DebitNoteController::class, 'storeFromExpired'])
    ->name('admin.debitnote.store.from.expired');

    Route::get('admin/debitnote/from-opening-stock', [DebitNoteController::class, 'createFromOpeningStock'])
    ->name('admin.debitnote.from.opening');

    Route::post('admin/debitnote/store-from-opening-stock', [DebitNoteController::class, 'storeFromOpeningStock'])
    ->name('admin.debitnote.store.from.opening');


//Delivery management
Route::get('delivery-manage', [DeliveryManagementController::class, 'index'])->name('delivery.index');


Route::get('new-delivery-manage', [DeliveryManagementController::class, 'new_index'])->name('new.delivery.index');



Route::get('admin/delivery', [DeliveryManagementController::class, 'index_check'])->name('admin.delivery.new_index');
Route::get('admin/delivery/data', [DeliveryManagementController::class, 'index_data'])->name('admin.delivery.new_index.data');



Route::get('delivery-manage/create', [DeliveryManagementController::class, 'create'])->name('delivery.create');
Route::post('delivery', [DeliveryManagementController::class, 'store'])->name('delivery.store');
Route::put('/deliveries/{id}', [DeliveryManagementController::class, 'update'])->name('update.delivery');
Route::get('/orders-data/{orderId}', [DeliveryManagementController::class, 'getOrderData']);
Route::post('/accept-order/{orderId}', [OrderItemController::class, 'acceptOrder'])->name('order.accept');
Route::post('/cancel-order/{orderId}', [OrderItemController::class, 'cancelOrder'])->name('order.cancel');


Route::get('/pick-list', [PickListController::class, 'index'])->name('pick.list');
Route::post('/pick-list/{id}/picked', [PickListController::class, 'markPicked'])->name('pick.list.picked');
Route::get('/pick-list-preview/{orderId}', [PickListController::class, 'preview'])
    ->name('pick.list.preview');
Route::post('/pick-list-preview/save', [PickListController::class, 'storePreview'])
    ->name('pick.list.preview.save');
    
        Route::post('/pick-list/update', [PickListController::class, 'updatePickList'])
    ->name('pick.list.update');
    
    Route::get('/check-pick-list/{orderId}', [PickListController::class, 'checkPickList'])
    ->name('pick.list.check');
Route::post('/pick-list-preview-pdf', [PickListController::class, 'printPreviewPdf'])
    ->name('pick.list.preview.pdf');
        Route::get('/pick-list/{orderId}/view', [PickListController::class, 'view'])
    ->name('pick.list.view');

   Route::get('/pick-list/{orderId}/edit', [PickListController::class, 'edit'])
    ->name('pick.list.edit');


Route::get('/admin/logistics', [LogisticController::class, 'index'])
     ->name('admin.logistics.index');    
Route::post('/order-logistics/store', [LogisticController::class, 'store'])->name('order.logistics.store');
Route::post('/delivery-modes/store', [DeliveryModeController::class, 'store'])->name('delivery.modes.store');
Route::get('/delivery-modes/list', [DeliveryModeController::class, 'list'])->name('delivery.modes.list');
Route::post('/delivery-modes/delete', [DeliveryModeController::class, 'delete'])
    ->name('delivery.modes.delete');

Route::post('/order-logistics/store-single', [LogisticController::class, 'storeSingle'])
     ->name('order.logistics.store.single'); 
     
Route::get('/admin/logistics/export', [LogisticController::class, 'export'])
    ->name('logistics.export');
    
Route::post('/admin/order/logistics/update-status', [LogisticController::class, 'updateStatus'])->name('order.logistics.update.status');

    


Route::get('/delivery/edit/{id}', [DeliveryManagementController::class, 'edit'])->name('delivery.edit');
Route::put('/delivery/{id}', [DeliveryManagementController::class, 'deliveryupdate'])->name('delivery.update-pay');
 
// Add export feature for customer outlet:
Route::get('/export-create-customer', [CustomerAuthController::class, 'exportCustomerCreate'])->name('export.create_customer');    

Route::get('/convert', [NumberToWordsController::class, 'showConvertForm'])->name('convert');
Route::post('/convert', [NumberToWordsController::class, 'convertNumberToWords']);
Route::post('productrequest', [ProductRequestController::class, 'store'])->name('productrequest.store');
Route::get('/requestedproduct', [ProductRequestController::class, 'index'])->name('admin.requestedproduct');
Route::put('/requestedproduct/update/{status}', [ProductRequestController::class, 'statusUpdate'])->name('requestedProducts.statusUpdate');

Route::get('customer-price/bulk-export', [ProductRequestController::class, 'bulkExport'])->name('customer.price.bulk.export');

Route::post('customer-price/bulk-import', [ProductRequestController::class, 'bulkImport'])->name('customer.price.bulk.import');

Route::get('customer-price/sample', [ProductRequestController::class, 'downloadSample'])->name('customer.price.sample');  

Route::get('/admin/customer/price', [ProductRequestController::class, 'customer_price'])->name('admin.customer.price');
Route::get('/admin/customer/create', [ProductRequestController::class, 'customer_price_create'])->name('admin.customer.price.create');
Route::post('customer-price/store', [ProductRequestController::class, 'customer_price_store'])
->name('customer.price.store');
Route::get('customer-price/{customer}/edit',[ProductRequestController::class, 'customer_price_edit'])->name('customer.price.edit');
Route::put('customer-price/{customer}',[ProductRequestController::class, 'customer_price_update'])->name('customer.price.update');
Route::delete('customer-price/{customer}',[ProductRequestController::class, 'customer_price_delete'])->name('customer.price.delete');
Route::get('customer-price/{customer}/export',[ProductRequestController::class, 'customer_price_export'])->name('customer.price.export');
Route::get('/admin/customer-prices/{customerId}', [ProductRequestController::class, 'getCustomerPrices']);    
Route::get('/admin/customer-price-locks/{customerId}',[ProductRequestController::class, 'getCustomerPriceLocks']);

Route::get('/coupons', [CouponController::class, 'index']);
Route::get('/coupons-create', [CouponController::class, 'create'])->name('coupons.create');
Route::post('/coupons', [CouponController::class, 'store'])->name('coupons.store');
Route::get('/coupons/{id}', [CouponController::class, 'edit'])->name('coupons.edit');
Route::get('/update-cart-discount/{couponCode}', [CartController::class, 'updateDiscount'])->name('coupons.updateDiscount');
Route::put('/coupons/{id}', [CouponController::class, 'update'])->name('coupons.update');
Route::delete('/delete-coupons/{id}', [CouponController::class, 'destroy']);
Route::post('/coupon-users', [CouponUserController::class, 'store'])->name('couponUsers.store');
Route::post('/coupon-validation', [CouponUserController::class, 'couponValidation'])->name('couponValidation');


Route::get('/admin/invoice', [SalesInvoiceController::class, 'index'])->name('admin.invoice');
Route::get('/admin/invoice/create', [SalesInvoiceController::class, 'invoice_create'])->name('admin.invoice.create');
Route::get('/admin/get-outlets/{customerId}', [SalesInvoiceController::class, 'getOutletsByCustomer']);
Route::get('/admin/get-outlet-details/{outletId}', [SalesInvoiceController::class, 'getOutletDetails']);
Route::get('/admin/products/list', [SalesInvoiceController::class, 'getProducts']);
// Route::get('/admin/customer-product-price/{customerId}/{productId}',[SalesInvoiceController::class, 'getCustomerProductPrice']);

Route::get('/admin/customer-product-price/{customerId}/{outletId}/{productId}',[SalesInvoiceController::class, 'getCustomerProductPrice']);
Route::get('/admin/check-price-change-log/{productId}', [SalesInvoiceController::class, 'checkPriceChangeLog']);

Route::get('/admin/get-delivery-charges/{pincode}', [SalesInvoiceController::class, 'getDeliveryCharges']);
Route::get('/admin/get-delivery-slots/{pincode}',[SalesInvoiceController::class, 'getDeliverySlots']);
Route::get('/admin/get-customer-credit/{customer}',[SalesInvoiceController::class, 'getCustomerCredit'])->name('admin.customer.credit');
Route::post('/admin/place-order',[SalesInvoiceController::class, 'placeorder'])->name('admin.place.order');
Route::get('/admin/orders/{id}', [SalesInvoiceController::class, 'show'])->name('admin.orders.show');
Route::get('/admin/orders/{id}/edit', [SalesInvoiceController::class, 'edit'])->name('admin.orders.edit');
Route::put('/admin/orders/{id}', [SalesInvoiceController::class, 'update'])->name('admin.orders.update');

Route::delete('/admin/orders/{id}', [SalesInvoiceController::class, 'destroy'])->name('admin.orders.destroy');
// Route::get('/admin/products/by-customer/{customerId}', [SalesInvoiceController::class, 'getProductsByCustomer']);
Route::get('/admin/products/by-customer/{customerId}/{outletId}',[SalesInvoiceController::class, 'getProductsByCustomer']);



Route::get('/purchase/orders', [PurchaseOrderController::class, 'index'])->name('admin.purchase-orders.index');
Route::get('/purchase/orders/create', [PurchaseOrderController::class, 'create'])->name('admin.purchase-orders.create');
Route::get('/vendors/{vendor}/details', [PurchaseOrderController::class, 'details']);
Route::get('/vendors/{vendor}/products', [PurchaseOrderController::class, 'products']);
Route::get('/vendors/{vendor}/credit-eligibility', [PurchaseOrderController::class, 'creditEligibility']);
Route::post('/admin/purchase-orders',[PurchaseOrderController::class, 'place_purchaseorder'])->name('admin.purchase-orders');
Route::get('/admin/purchase-orders/{id}/show',[PurchaseOrderController::class, 'show_purchaseorder'])->name('admin.purchase-orders.show');
Route::get('/admin/purchase-orders/{id}/edit',[PurchaseOrderController::class, 'edit_purchaseorder'])->name('admin.purchase-orders.edit');
Route::delete('/admin/purchase-orders/{id}',[PurchaseOrderController::class, 'destroy_purchaseorder'])->name('admin.purchase-orders.destroy');
Route::put('/admin/purchase-orders/update/{id}',[PurchaseOrderController::class, 'update_purchaseorder'])->name('admin.purchase-orders.update');
Route::get('/admin/purchase-orders/{purchaseOrder}/for-stock-receiving',[PurchaseOrderController::class, 'forStockReceiving']
)->name('admin.purchase-orders.for-stock-receiving');
Route::get('/purchase/orders/approval', [PurchaseOrderController::class, 'approval_purchaseorder'])->name('admin.purchase-orders.approval');
Route::get('/admin/purchase-orders/{id}/details', [PurchaseOrderController::class, 'details_purchaseorder'])->name('admin.purchase-orders.review');
Route::post('/admin/purchase-orders/{id}/review-submit',[PurchaseOrderController::class, 'submitReview_purchaseorder'])->name('admin.purchase-orders.review.submit');
Route::get('/purchase-order-pdf/{id}', [PurchaseOrderController::class, 'downloadPdf'])->name('admin.purchase-orders.pdf');

Route::post('/admin/po/add-draft-item', [PurchaseOrderController::class,'addDraftItem'])->name('admin.po.addDraftItem');

// Route::get('/admin/check-product-rack-allocation/{productId}', [PurchaseOrderController::class, 'checkProductAllocation']);
Route::get('/admin/check-any-rack-allocation', [PurchaseOrderController::class, 'checkAnyPendingRackAllocation']);



Route::get('/admin/stock', [StockController::class, 'index'])->name('admin.stock-receivings.index');

Route::get('/admin/stock-receivings/pending', [StockController::class, 'stock_receivings_pending'])->name('admin.stock-receivings.pending');
Route::get('/admin/stock/create', [StockController::class, 'create'])->name('stock.create');
Route::post('/stock/store', [StockController::class, 'store'])->name('stock.store');

Route::post('/admin/stock-receivings', [StockController::class, 'stock_receivings'])->name('admin.stock-receivings');
Route::get('/admin/stock-receivings/{id}',[StockController::class, 'stock_show'])->name('admin.stock-receivings.show');
Route::post('/admin/convert-to-bill/{id}',[StockController::class, 'stock_convert_to_bill'])->name('admin.stock-receivings.convert-to-bill');
Route::get('/admin/live-stock',[StockController::class, 'liveStock'])->name('admin.stock.live-stock');
Route::get('/admin/stock-live', [RackReceivingController::class, 'liveStockReport']) ->name('admin.rack.live-location');
Route::get('/admin/stock-ledger',[StockController::class, 'stockLedger'])->name('admin.stock-receivings.ledger');
Route::get('/stock-receivings/bills',[StockController::class, 'stock_receivings_bills'])->name('admin.stock-receivings.bills');

Route::post('/stock-ledger/return/{id}', [StockController::class, 'markReturned'])->name('stock.markReturned');
    
Route::post('/stock/create-debit-note/{id}', [StockController::class, 'createDebitNote'])->name('stock.createDebitNote');
    
// Route::get('/pre-short-material-log', [YourController::class, 'method'])->name('pre.short.material.log');
Route::get('/admin/stock-receivings/bills/{bill}',[StockController::class, 'stock_receivings_bill_show'])->name('admin.stock-receivings.bills.show');
Route::post('/admin/stock-receivings/{grn}/review',[StockController::class, 'stock_receivings_review_submit'])->name('admin.stock-receivings.review.submit');

Route::get('/admin/stock-receivings/bills/{bill}/edit',[StockController::class, 'stock_receivings_bill_edit'])->name('admin.stock-receivings.bills.edit');
Route::put('/admin/stock-receivings/bills/update/{bill}',[StockController::class, 'stock_receivings_bill_update'])->name('admin.stock-receivings.bills.update');

// Route::get('/pre-short-material-log', [PreShortMaterialLogController::class, 'index'])
//     ->name('pre.short.material.log');

    Route::get('/pre-short-material-log', [ShortMaterialLogController::class, 'preindex'])
    ->name('pre.short.material.log');
    
       Route::post('/pre-short-log/update', [ShortMaterialLogController::class, 'updateLog'])
    ->name('pre.short.log.update');
    Route::post('/post-short-material/save', [ShortMaterialLogController::class, 'savePostShortLog'])
    ->name('post.short.material.save');

    

// Route::get('/pre-short-material-log', [ShortMaterialLogController::class, 'index'])
//     ->name('pre.short.material.log');   
Route::get('/admin/stock-receivings/{id}/edit', [StockController::class, 'stock_receivings_edit'])->name('admin.stock-receivings.edit');

Route::put('/admin/stock-receivings/update/{id}', [StockController::class, 'stock_receivings_update'])->name('admin.stock-receivings.update');
Route::delete('/admin/stock-receivings/destroy/{id}', [StockController::class, 'stock_receivings_destroy'])->name('admin.stock-receivings.destroy');


Route::get('/admin/stock-opening', [StockController::class, 'stock_opening'])->name('admin.stock-opening');
Route::get('/admin/stock-opening/create', [StockController::class, 'stock_opening_create'])->name('admin.stock-opening.create');
Route::post('/admin/stock-opening/store', [StockController::class, 'stock_opening_store'])->name('admin.stock-opening.store');
Route::get('/admin/stock-opening/export', [StockController::class, 'export'])->name('admin.stock-opening.export');
Route::post('/admin/stock-opening/import', [StockController::class, 'import'])->name('admin.stock-opening.import');


// Lead Customer
Route::prefix('admin')->group(function () {
    
    Route::get('quotations', [QuotationController::class, 'index'])->name('quotations.index');
    Route::get('quotations/create', [QuotationController::class, 'create'])->name('quotations.create');
    Route::post('quotations', [QuotationController::class, 'store'])->name('quotations.store');
    Route::get('quotations/{id}/edit', [QuotationController::class, 'edit'])->name('quotations.edit');
    Route::put('quotations/{id}', [QuotationController::class, 'update'])->name('quotations.update');
    Route::delete('quotations/{id}', [QuotationController::class, 'destroy'])->name('quotations.destroy');
    
    
    Route::get('quotations/{id}/view', [QuotationController::class, 'show'])->name('quotations.show');
    Route::get('quotations/{id}/invoice', [QuotationController::class, 'invoice'])->name('quotations.invoice');


    Route::get('lead-customer-details/{id}', [QuotationController::class, 'getLeadCustomerDetails'])->name('lead-customer.details');
    Route::get('products/for-quotation', [QuotationController::class, 'getProductsForQuotation'])->name('products.for-quotation');
    
    Route::get('admin/quotation/{id}/excel', [QuotationController::class, 'exportExcel'])
    ->name('admin.quotation.excel');
    
    
    Route::get('lead-customer', [LeadCustomerController::class, 'index'])->name('lead-customers.index');
    Route::get('lead-customer/create', [LeadCustomerController::class, 'create'])->name('lead-customers.create');
    Route::post('lead-customer', [LeadCustomerController::class, 'store'])->name('lead-customers.store');
    Route::get('lead-customer/{id}/edit', [LeadCustomerController::class, 'edit'])->name('lead-customers.edit');
    Route::put('lead-customer/{id}', [LeadCustomerController::class, 'update'])->name('lead-customers.update');
    Route::delete('lead-customer/{id}', [LeadCustomerController::class, 'destroy'])->name('lead-customers.destroy');
});

// Stock Adjustment
Route::get('/admin/stock-adjustment', [StockController::class, 'stockAdjustmentIndex'])->name('admin.stock-adjustment.index');
Route::get('/admin/stock-adjustment/create/{product}', 
    [StockController::class, 'stockAdjustmentCreate'])
    ->name('admin.stock-adjustment.create');
Route::get('/admin/stock-adjustment/product-locations', 
    [StockController::class, 'getProductLocations']
)->name('admin.stock-adjustment.product-locations');
Route::post('/admin/stock-adjustment/store', [StockController::class, 'stockAdjustmentStore'])
    ->name('admin.stock-adjustment.store');
    
        // Stock Transfer
Route::get('stock-transfer', [StockTransferController::class, 'index'])
->name('admin.stock-transfer.index');

Route::get('stock-transfer/create/{product}', [StockTransferController::class, 'create'])
->name('admin.stock-transfer.create');

Route::post('stock-transfer/store', [StockTransferController::class, 'store'])
->name('admin.stock-transfer.store');

// short material function: 

Route::get('/short-material-log/export', [ShortMaterialLogController::class, 'export'])->name('short.material.log.export');

// use for data :
Route::get('/short-material-log/{orderId}', [ShortMaterialLogController::class, 'show'])->name('short.material.log.show');

// Add export to excel feature in create vendor:
Route::get('/export-vendors', [VendorController::class, 'exportVendors'])->name('export.vendors');

 // short material
Route::get('/short-material-log', [ShortMaterialLogController::class, 'index'])
    ->name('short.material.log');

    Route::get('/short-material-log/{orderId}',
    [ShortMaterialLogController::class, 'show'])
    ->name('short.material.log.show');
    
 // customer sales report
Route::get('/customer-sales-report', [CustomerSalesReportController::class, 'index'])
    ->name('customer.sales.report');

Route::get('/customer-sales-report/pdf/{id}', [CustomerSalesReportController::class, 'downloadPdf'])
    ->name('customer.sales.report.pdf');    


// Rack Receiving
Route::get('/admin/rack-receiving', [RackReceivingController::class, 'index'])->name('admin.rack.receiving.index');
Route::get('/admin/rack-receiving/{grn}/create', [RackReceivingController::class, 'create'])->name('admin.rack.receiving.create');
Route::post('/admin/rack-receiving/{grn}', [RackReceivingController::class, 'store'])->name('admin.rack.receiving.store');
Route::get('/admin/rack-receiving/{grn}/show', [RackReceivingController::class, 'show'])->name('admin.rack.receiving.show');
Route::get('rack-receiving/{grn}/edit', [RackReceivingController::class, 'edit'])
    ->name('admin.rack.receiving.edit');
    Route::post('rack-receiving/{grn}/update', [RackReceivingController::class, 'update'])
    ->name('admin.rack.receiving.update');

Route::get('/admin/stock-location', [RackReceivingController::class, 'liveStock']) ->name('admin.rack.stock-location');
Route::get('/admin/stock/product/{product}', [RackReceivingController::class, 'productStockDetail'])->name('admin.stock.product.detail');
Route::get('/rack-stock-history/{product_id}', [RackReceivingController::class, 'history'])
    ->name('rack.stock.history');





Route::get('/vendor_user', [VendorController::class, 'index'])->name('vendors.index');
Route::get('/vendor_user/create', [VendorController::class, 'create'])->name('vendors.create');
Route::post('/vendor_user', [VendorController::class, 'store'])->name('vendors.store');
Route::get('/vendor_user/{id}/edit', [VendorController::class, 'edit']) ->name('vendors.edit');
Route::put('/vendor_user/{id}', [VendorController::class, 'update'])->name('vendors.update');
Route::get('/vendors/export-payment', [VendorController::class, 'exportVendorPayment'])->name('vendors.export.payment');
Route::put('/vendor_user/{vendor}', [VendorController::class, 'update'])->name('vendors.update');
Route::delete('/vendor_user/{vendor}', [VendorController::class, 'destroy'])->name('vendors.destroy');

Route::get('/vendor_user/{id}/payment_term', [VendorController::class, 'payment_term']) ->name('vendors.payment_term');
Route::post('/vendors/{vendor}/payment-terms',[VendorController::class, 'storePaymentTerm'])->name('vendor.payment-term.save');


Route::get('vendor-price/bulk-export', [VendorPriceListController::class, 'bulkExport'])
    ->name('vendor.price.bulk.export');
Route::post('vendor-price/bulk-import', [VendorPriceListController::class, 'bulkImport'])
    ->name('vendor.price.bulk.import');
Route::get('vendor-price/sample', [VendorPriceListController::class, 'downloadSample'])
    ->name('vendor.price.sample');  
    

Route::get('/admin/vendor-price-locks/{vendorId}',[VendorPriceListController::class, 'getVendorPriceLocks'])->name('vendor.price.locks');
Route::get('/vendor_price', [VendorPriceListController::class, 'index'])->name('vendor.price.index');
Route::get('/vendor_price/create', [VendorPriceListController::class, 'create'])->name('vendor.price.create');
Route::post('/vendor_price/store', [VendorPriceListController::class, 'store'])->name('vendor.price.store');
Route::get('/vendor_price/{id}/edit', [VendorPriceListController::class, 'edit'])->name('vendor.price.edit');
Route::put('/{id}', [VendorPriceListController::class, 'update'])->name('vendor.price.update');
Route::delete('/{id}', [VendorPriceListController::class, 'destroy'])->name('vendor.price.destroy');
Route::get('vendor-price/{vendor}/export',[VendorPriceListController::class, 'vendor_price_export'])->name('vendor.price.export');

 Route::get('/admin/vendor-payments', [VendorPriceListController::class, 'vendor_payments_index'])->name('admin.vendor-payments.index');
 Route::post('/admin/vendor-payments', [VendorPriceListController::class, 'vendor_payments_store'])->name('admin.vendor-payments.store');
 Route::get('/admin/vendor-payments/{vendorBill}',[VendorPriceListController::class, 'vendor_payments_byBill'])->name('admin.vendor-payments.by-bill');

 Route::get('/admin/vendor-payments/{bill}/create',[VendorPriceListController::class, 'vendor_payments_create'])->name('admin.vendor-payments.create');
 Route::get('/vendor-payments/{bill}',[VendorPriceListController::class, 'vendor_payments_show'])->name('admin.vendor-payments.show');
 Route::post('/admin/vendor-payments',[VendorPriceListController::class, 'vendor_payments_store'])->name('admin.vendor-payments.store');

//pages

Route::get('terms-condition', [HomeController::class, 'terms_condition'])->name('terms-condition');
Route::get('shipping-policy', [HomeController::class, 'shipping_policy'])->name('shipping-policy');
Route::get('privacy_policy', [HomeController::class, 'privacy_policy'])->name('privacy-policy');
Route::get('return-replacement', [HomeController::class, 'refund'])->name('refund');
Route::get('payments-refunds', [HomeController::class, 'payment'])->name('payment');


Route::get('/payment', [RazorpayController::class, 'index']);
Route::post('/payment', [RazorpayController::class, 'createOrder'])->name('payment.createOrder');
Route::post('/update-order-payment', [RazorpayController::class, 'updateOrderPayment'])->name('update.order.payment');
Route::post('payment/success', [RazorpayController::class, 'paymentSuccess']);
Route::post('/payment/success', [RazorpayController::class, 'handlePaymentSuccess'])->name('payment.success');
Route::post('/create-order', [RazorpayController::class, 'createOrder']);
Route::post('/razorpay-callback', [RazorpayController::class, 'razorpayCallback']);
Route::post('/razorpay/payment/success', [RazorpayController::class, 'handlePaymentSuccess'])->name('razorpay.payment.success');



Route::post('/updatepay-order', [RazorpayController::class, 'updatepaymethod'])->name('updatepay.order');

Route::match(['get', 'post'], '/handle-payment-update', [RazorpayController::class, 'handlePaymentUpdate'])
    ->name('handle.payment.update');






// Brands Associated
Route::resource('brandsassoc', BrandsassocController::class);
Route::get('/brandslogo/{id}/edit', [BrandsassocController::class, 'edit'])->name('brandassoc.edit');
Route::post('brandslogo/store', [BrandsassocController::class, 'store'])->name('brandassoc.store');
Route::put('/brandslogo/{id}', [BrandsassocController::class, 'update'])->name('brandsassoc.update');
Route::delete('delete-brandsassoc/{id}', [BrandsassocController::class, 'destroy']);


// Clients Serve
Route::resource('clientsserve', ClientserveController::class);
Route::get('/clientslogo/{id}/edit', [ClientserveController::class, 'edit'])->name('clientserve.edit');
Route::post('clientslogo/store', [ClientserveController::class, 'store'])->name('clientserve.store');
Route::put('/clientslogo/{id}', [ClientserveController::class, 'update'])->name('clientserve.update');
Route::delete('delete-clientslogo/{id}', [ClientserveController::class, 'destroy']);
Route::post('offer/reoffer1/{enquiry}', [EnquiryController::class, 'offerreoffer1'])->name('offer.reoffer1');


Route::get('/storage/gst_docs/{filename}', function ($filename) {
    $path = storage_path('app/gst_docs/' . $filename);
    if (file_exists($path)) {
        return response()->file($path);
    }
    abort(404);
});

Route::get('/storage/pancard_docs/{filename}', function ($filename) {
    $path = storage_path('app/pancard_docs/' . $filename);
    if (file_exists($path)) {
        return response()->file($path);
    }
    abort(404);
});

Route::get('/storage/fssai_docs/{filename}', function ($filename) {
    $path = storage_path('app/fssai_docs//' . $filename);
    if (file_exists($path)) {
        return response()->file($path);
    }
    abort(404);
});





