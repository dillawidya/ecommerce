<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\ItemController;
use App\Http\Controllers\admin\PageController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\ReportController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\PurchaseController;
use App\Http\Controllers\admin\ShippingController;
use App\Http\Controllers\admin\SupplierController;
use App\Http\Controllers\admin\TransaksiController;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\admin\ItemProductController;
use App\Http\Controllers\admin\ManageStockController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\DiscountCodeController;
use App\Http\Controllers\admin\OrderSupplierController;
use App\Http\Controllers\admin\PurchaseDetailController;
use App\Http\Controllers\supplier\HomeSupplierController;
use App\Http\Controllers\supplier\SupplierLoginController;
use App\Http\Controllers\supplier\SupplierProductController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/test', function () {
//     orderEmail(17);
// });

Route::get('/',[FrontController::class, 'index'])->name('front.home');
Route::get('/shop/{categorySlug?}',[ShopController::class, 'index'])->name('front.shop');
Route::get('/product/{id}',[ShopController::class, 'product'])->name('front.product');
Route::get('/cart',[CartController::class, 'cart'])->name('front.cart');
Route::post('/add-to-cart',[CartController::class, 'addToCart'])->name('front.addToCart');
Route::post('/update-cart',[CartController::class, 'updateCart'])->name('front.updateCart');
Route::post('/delete-item',[CartController::class, 'deleteItem'])->name('front.deleteItem.cart');
Route::get('/checkout',[CartController::class, 'checkout'])->name('front.checkout');
Route::post('/process-checkout',[CartController::class, 'processCheckout'])->name('front.processCheckout');
Route::get('/thanks/{orderId}',[CartController::class, 'thankyou'])->name('front.thankyou');
Route::post('/get-order-summery',[CartController::class, 'getOrderSummery'])->name('front.getOrderSummery');
Route::post('/apply-discount',[CartController::class, 'applyDiscount'])->name('front.applyDiscount');
Route::post('/remove-discount',[CartController::class, 'removeCoupon'])->name('front.removeCoupon');
Route::post('/add-to-wishlist',[FrontController::class, 'addToWishlist'])->name('front.addToWishlist');
Route::get('/page/{slug}',[FrontController::class, 'page'])->name('front.page');
Route::post('/send-contact-email',[FrontController::class, 'sendContactEmail'])->name('front.sendContactEmail');

Route::get('/forgot-password',[AuthController::class, 'forgotPassword'])->name('front.forgotPassword');
Route::post('/process-forgot-password',[AuthController::class, 'processForgotPassword'])->name('front.processForgotPassword');
Route::get('/reset-password/{token}',[AuthController::class, 'resetPassword'])->name('front.resetPassword');
Route::post('/process-password',[AuthController::class, 'processResetPassword'])->name('front.processResetPassword');
Route::post('/save-rating/{productId}',[ShopController::class, 'saveRating'])->name('front.saveRating');


Route::group(['prefix' => 'account'], function(){
    Route::group(['middleware' => 'guest'], function(){
        Route::get('/login', [AuthController::class, 'login'])->name('account.login');
        Route::post('/login', [AuthController::class, 'authenticate'])->name('account.authenticate');

        Route::get('/register', [AuthController::class, 'register'])->name('account.register');
        Route::post('/process-register', [AuthController::class, 'processRegister'])->name('account.processRegister');
       
    });

    Route::group(['middleware' => 'auth'], function(){
        Route::get('/profile', [AuthController::class, 'profile'])->name('account.profile');
        Route::post('/update-profile', [AuthController::class, 'updateProfile'])->name('account.updateProfile');
        Route::post('/update-address', [AuthController::class, 'updateAddress'])->name('account.updateAddress');
        Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('account.showChangePasswordForm');
        Route::post('/process-change-password', [AuthController::class, 'changePassword'])->name('account.processChangePassword');

        Route::get('/my-orders', [AuthController::class, 'orders'])->name('account.orders');
        Route::get('/coupons', [AuthController::class, 'coupons'])->name('account.coupons');
        Route::get('/my-wishlist', [AuthController::class, 'wishlist'])->name('account.wishlist');
        Route::post('/remove-product-from-wishlist', [AuthController::class, 'removeProductFromWishlist'])->name('account.removeProductFromWishlist');
        Route::get('/order-detail/{orderId}', [AuthController::class, 'orderDetail'])->name('account.orderDetail');
        Route::get('/logout', [AuthController::class, 'logout'])->name('account.logout');


    });
});


Route::group(['prefix' => 'supplier'], function(){

    Route::group(['middleware' => 'supplier.guest'], function(){
        Route::get('/login',[SupplierLoginController::class, 'index'])->name('supplier.login');
        Route::post('/authenticate',[SupplierLoginController::class, 'authenticate'])->name('supplier.authenticate');

    });

    Route::group(['middleware' => 'supplier.auth'], function(){
        Route::get('/logout',[HomeSupplierController::class, 'logout'])->name('supplier.logout');
        Route::get('/dashboard',[HomeSupplierController::class, 'index'])->name('supplier.dashboard');

        //Route productsupplier
        Route::get('/supplier-products', [SupplierProductController::class, 'index'])->name('supplier-products.index');
        Route::post('/supplier-products', [SupplierProductController::class, 'store'])->name('supplier-products.store');
        Route::get('/supplier-products/{item}/edit',[SupplierProductController::class, 'edit'])->name('supplier-products.edit');
        Route::put('/supplier-products/{item}',[SupplierProductController::class, 'update'])->name('supplier-products.update');
        Route::get('/product-manage/{item}/edit',[SupplierProductController::class, 'manage'])->name('product-manage.edit');
        Route::put('/product-manage/{item}',[SupplierProductController::class, 'updateManage'])->name('product-manage.update');
        // Route::delete('/supplier-products/{item}',[SupplierProductController::class, 'destroy'])->name('supplier-products.delete');


    });

});

Route::group(['prefix' => 'admin'], function(){

    Route::group(['middleware' => 'admin.guest'], function(){
        Route::get('/login',[AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate',[AdminLoginController::class, 'authenticate'])->name('admin.authenticate');

    }); 

    Route::group(['middleware' => 'admin.auth'], function(){
        Route::get('/dashboard',[HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout',[HomeController::class, 'logout'])->name('admin.logout');

        //Category Routes
        Route::get('/categories',[CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create',[CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories',[CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit',[CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}',[CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}',[CategoryController::class, 'destroy'])->name('categories.delete');


        //Sub Category Routes
        Route::get('/sub-categories',[SubCategoryController::class, 'index'])->name('sub-categories.index');
        Route::get('/sub-categories/create',[SubCategoryController::class, 'create'])->name('sub-categories.create');
        Route::post('/sub-categories',[SubCategoryController::class, 'store'])->name('sub-categories.store');
        Route::get('/sub-categories/{subCategory}/edit',[SubCategoryController::class, 'edit'])->name('sub-categories.edit');
        Route::put('/sub-categories/{subCategory}',[SubCategoryController::class, 'update'])->name('sub-categories.update');
        Route::delete('/sub-categories/{subCategory}',[SubCategoryController::class, 'destroy'])->name('sub-categories.delete');


        //Brands Routes
        Route::get('/brands',[BrandController::class, 'index'])->name('brands.index');
        Route::get('/brands/create',[BrandController::class, 'create'])->name('brands.create');
        Route::post('/brands',[BrandController::class, 'store'])->name('brands.store');
        Route::get('/brands/{brand}/edit',[BrandController::class, 'edit'])->name('brands.edit');
        Route::put('/brands/{brand}',[BrandController::class, 'update'])->name('brands.update');
        Route::delete('/brands/{brand}',[BrandController::class, 'destroy'])->name('brands.delete');

       
        //Items Routes
        Route::get('/item-products',[ItemProductController::class, 'index'])->name('item-products.index');
        Route::get('/item-products/create',[ItemProductController::class, 'create'])->name('item-products.create');
        Route::post('/item-products',[ItemProductController::class, 'store'])->name('item-products.store');
        Route::get('/item-products/{Item}/edit',[ItemProductController::class, 'edit'])->name('item-products.edit');
        Route::put('/item-products/{Item}',[ItemProductController::class, 'update'])->name('item-products.update');
        Route::delete('/item-products/{Item}',[ItemProductController::class, 'destroy'])->name('item-products.delete');


        //Suppliers Routes
        Route::get('/suppliers',[SupplierController::class, 'index'])->name('suppliers.index');
        Route::get('/suppliers/create',[SupplierController::class, 'create'])->name('suppliers.create');
        Route::post('/suppliers',[SupplierController::class, 'store'])->name('suppliers.store');
        Route::get('/suppliers/{supplier}/edit',[SupplierController::class, 'edit'])->name('suppliers.edit');
        Route::put('/suppliers/{supplier}',[SupplierController::class, 'update'])->name('suppliers.update');
        Route::delete('/suppliers/{supplier}',[SupplierController::class, 'destroy'])->name('suppliers.delete');

        //OrderSupplier
        Route::get('/order-supplier', [OrderSupplierController::class, 'index']);
        Route::post('/order-supplier/send', [OrderSupplierController::class, 'send']);

        // Transaksi Routes
        Route::get('/transaksi',[TransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('/transaksi/create',[TransaksiController::class, 'create'])->name('transaksi.create');
        Route::post('/transaksi/store',[TransaksiController::class, 'store'])->name('transaksi.store');
        Route::post('/transaksi/{id}',[TransaksiController::class, 'update'])->name('transaksi.update');
        Route::delete('/transaksi/destroy{id}',[TransaksiController::class, 'destroy'])->name('transaksi.destroy');

        
        //Product Routes
        Route::get('/products',[ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create',[ProductController::class, 'create'])->name('products.create');
        Route::post('/products',[ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit',[ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}',[ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}',[ProductController::class, 'destroy'])->name('products.delete');
        Route::get('/get-products',[ProductController::class, 'getProducts'])->name('products.getProducts');
        Route::get('/ratings',[ProductController::class, 'productRatings'])->name('products.productRatings');
        Route::get('/change-rating-status',[ProductController::class, 'changeRatingStatus'])->name('products.changeRatingStatus');


        //shipping Routes
        Route::get('/shipping/create',[ShippingController::class, 'create'])->name('shipping.create');
        Route::post('/shipping',[ShippingController::class, 'store'])->name('shipping.store');
        Route::get('/shipping/{id}',[ShippingController::class, 'edit'])->name('shipping.edit');
        Route::put('/shipping/{id}',[ShippingController::class, 'update'])->name('shipping.update');
        Route::delete('/shipping/{id}',[ShippingController::class, 'destroy'])->name('shipping.delete');

        
        //Manage Stock Routes
        Route::get('/manage-stocks',[ManageStockController::class, 'index'])->name('manage-stocks.index');
        Route::get('/manage-stocks/create',[ManageStockController::class, 'create'])->name('manage-stocks.create');
        Route::post('/manage-stocks',[ManageStockController::class, 'store'])->name('manage-stocks.store');
        Route::get('/manage-stocks/{manageStock}/edit',[ManageStockController::class, 'edit'])->name('manage-stocks.edit');
        Route::put('/manage-stocks/{manageStocks}',[ManageStockController::class, 'update'])->name('manage-stocks.update');
        Route::delete('/manage-stocks/{manageStocks}',[ManageStockController::class, 'destroy'])->name('manage-stocks.delete');



        //Coupon Code Routes
        Route::get('/coupons',[DiscountCodeController::class, 'index'])->name('coupons.index');
        Route::get('/coupons/create',[DiscountCodeController::class, 'create'])->name('coupons.create');
        Route::post('/coupons',[DiscountCodeController::class, 'store'])->name('coupons.store');
        Route::get('/coupons/{coupon}/edit',[DiscountCodeController::class, 'edit'])->name('coupons.edit');
        Route::put('/coupons/{coupon}',[DiscountCodeController::class, 'update'])->name('coupons.update');
        Route::delete('/coupons/{coupon}',[DiscountCodeController::class, 'destroy'])->name('coupons.delete');
        

        //Order Routes
        Route::get('/orders',[OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}',[OrderController::class, 'detail'])->name('orders.detail');
        Route::post('/order/change-status/{id}',[OrderController::class, 'changeOrderStatus'])->name('orders.changeOrderStatus');
        Route::post('/order/send-email/{id}',[OrderController::class, 'sendInvoiceEmail'])->name('orders.sendInvoiceEmail');

        //Report Routes
        Route::get('/report-profits',[ReportController::class, 'reportProfit'])->name('report-profits.reportProfit');
        Route::get('/report-purchases',[ReportController::class, 'reportPurchase'])->name('report-purchases.reportPurchase');


        //User Routes
        Route::get('/users',[UserController::class, 'index'])->name('users.index');
        Route::get('/users/create',[UserController::class, 'create'])->name('users.create');
        Route::post('/users',[UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit',[UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}',[UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}',[UserController::class, 'destroy'])->name('users.delete');


        //Page Routes
        Route::get('/pages',[PageController::class, 'index'])->name('pages.index');
        Route::get('/pages/create',[PageController::class, 'create'])->name('pages.create');
        Route::post('/pages',[PageController::class, 'store'])->name('pages.store');
        Route::get('/pages/{page}/edit',[PageController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{page}',[PageController::class, 'update'])->name('pages.update');
        Route::delete('/pages/{page}',[PageController::class, 'destroy'])->name('pages.delete');


        //Setting Route
        Route::get('/change-password',[SettingController::class, 'showChangePasswordForm'])->name('admin.showChangePasswordForm');
        Route::post('/process-change-password',[SettingController::class, 'processChangePassword'])->name('admin.processChangePassword');


        //temp-images.create
        Route::post('/upload-temp-image',[TempImagesController::class, 'create'])->name('temp-images.create');


        Route::get('/getSlug',function(Request $request){
            $slug = '';
            if (!empty($request->title)) {
                $slug = Str::slug($request->title);
            }
            return response()->json([
                'status' => true,
                'slug' => $slug
            ]);
        })->name('getSlug');
        
    });


});