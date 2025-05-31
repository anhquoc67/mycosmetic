<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CartController;
use Illuminate\Http\Request;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\UserController as FrontUserController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CosmeticController;
use App\Http\Controllers\HotController;
use App\Http\Controllers\HomeController;






// Route đăng nhập / đăng ký / đăng xuất
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Route sản phẩm
// Route::resource('products', ProductController::class);
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');

//Route đối với user đã đăng nhập để xem hóa đơn
Route::get('/account', [FrontUserController::class, 'account'])->name('user.account')->middleware('auth');
Route::get('/account/orders', [FrontUserController::class, 'orders'])->name('user.orders')->middleware('auth');
Route::get('/account/orders/{id}', [FrontUserController::class, 'viewOrder'])->name('user.orders.view')->middleware('auth');




// Route cho admin (CRUD hạn chế - cần quyền)
Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () { // chỉ cho Admin truy cập
    Route::resource('users', UserController::class); // CRUD Users
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::patch('/users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::resource('products', ProductController::class)->except(['show']); // CRUD Products trừ xem
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    // Route::get('users/{user}/orders', [UserController::class, 'orders'])->name('users.orders'); // Xem đơn hàng của 1 user
    Route::get('orders/user/{user}', [OrderController::class, 'index'])->name('orders.vieworders'); // Danh sách đơn hàng
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show'); // Xem chi tiết 1 đơn hàng
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus'); // Cập nhật trạng thái giao hàng
    Route::post('/admin/orders/{id}/mark-paid', [OrderController::class, 'markAsPaid'])->name('orders.markPaid'); // Đánh dấu đơn hàng đã thanh toán
});

// Route cho Admin tương tác với sản phẩm
    // Route for the SaleController
    Route::prefix('sales')->group(function () {
        Route::get('/', [SaleController::class, 'index']);
        Route::get('/create', [SaleController::class, 'create'])->name('sales.create');
        Route::post('/create', [SaleController::class, 'store'])->name('sales.store');

        Route::get('/edit/{id}', [SaleController::class, 'edit'])->name('sales.edit');
        Route::post('/edit/{id}', [SaleController::class, 'update'])->name('sales.update');

        Route::get('/delete/{id}', [SaleController::class, 'delete'])->name('sales.delete');
        Route::get('/detail/{id}', [SaleController::class, 'detail'])->name('sales.detail');
    });

    // Route for the HotController
    Route::prefix('hots')->group(function () {
        Route::get('/', [HotController::class, 'index'])->name('hots.index');           // Danh sách sản phẩm hot
        Route::get('/create', [HotController::class, 'create'])->name('hots.create');    // Form tạo mới sản phẩm hot
        Route::post('/store', [HotController::class, 'store'])->name('hots.store');      // Lưu sản phẩm hot mới
        Route::get('/detail/{id}', [HotController::class, 'detail'])->name('hots.detail'); // Chi tiết sản phẩm hot
        Route::get('/edit/{id}', [HotController::class, 'edit'])->name('hots.edit');     // Form sửa sản phẩm hot
        Route::post('/update/{id}', [HotController::class, 'update'])->name('hots.update'); // Cập nhật sản phẩm hot
        Route::get('/delete/{id}', [HotController::class, 'delete'])->name('hots.delete'); // Xóa sản phẩm hot
    });
    // Route cho phần quản lý sản phẩm
    Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    // Quản lý sản phẩm cho admin
    Route::get('/products', [CosmeticController::class,'index'])->name('products.index');
    Route::get('/products/create', [CosmeticController::class, 'create'])->name('products.create');
    Route::post('/products/store', [CosmeticController::class, 'store'])->name('products.store');
    Route::get('/products/edit/{id}', [CosmeticController::class,"edit"])->name('products.edit');
    Route::post('/products/update/{id}', [CosmeticController::class, 'update'])->name('products.update');
    Route::get('/products/detail/{id}', [CosmeticController::class,'detail'])->name('products.detail');
    Route::post('/products/delete/{id}', [CosmeticController::class, 'delete'])->name('products.delete');
    Route::get('/products/restore/{id}', [CosmeticController::class, 'restore'])->name('products.restore');

});


// Route cho phần thanh toán QR Code
Route::get('/checkout/confirm', [CheckoutController::class, 'confirmPayment'])->name('checkout.confirm');
Route::get('/checkout/qrcode', [CheckoutController::class, 'showQRCode'])->name('checkout.qrcode');
Route::get('/checkout/qr', [CheckoutController::class, 'showQRCode'])->name('checkout.qr');
Route::get('/checkout/payment-options', [CheckoutController::class, 'paymentOptions'])->name('checkout.payment');
// Hiển thị trang xác nhận sau khi quét QR (GET)
Route::get('/checkout/qr-confirm/{order_code}', [CheckoutController::class, 'showQrConfirm'])->name('checkout.qrConfirm');
// Xác nhận thật sự (POST)
Route::post('/checkout/qr-confirm/{order_code}', [CheckoutController::class, 'handleQrConfirm'])->name('checkout.qrConfirm.post');
Route::get('/order/invoice/{order_code}', [CheckoutController::class, 'showInvoice'])->name('order.invoice');




// Route sản phẩm mới
Route::get('/new', [NewController::class, 'index'])->name('new');

// Route sản phẩm sale
Route::get('/sale', [SaleController::class, 'index'])->name('sale');

// Route thêm giỏ hàng
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'view'])->name('cart.view');

// Route check out 
Route::get('/checkout', function () {return view('cart.checkout');})->name('checkout');
Route::post('/checkout/complete', [CartController::class, 'completeOrder'])->name('cart.completeOrder')->middleware('auth');
Route::post('/get-districts', [LocationController::class, 'getDistricts']);
Route::post('/get-wards', [LocationController::class, 'getWards']);
Route::post('/cart/save-location', [CartController::class, 'saveLocation'])->name('cart.saveLocation');


// Route report sp theo ngay, thang, nam
Route::get('/report/daily', [ReportController::class, 'dailyReport']);
Route::get('/report/weekly', [ReportController::class, 'weeklyReport']);
Route::get('/report/monthly', [ReportController::class, 'monthlyReport']);
Route::get('/report/yearly', [ReportController::class, 'yearlyReport']);
Route::get('/report/quarterly', [ReportController::class, 'quarterlyReport']);
Route::get('/report/stock', [ReportController::class, 'stockReport']);

// Route trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// Route Search
Route::get('/search', function (Request $request) {
    $query = $request->input('query');
    return view('search', ['query' => $query]);
})->name('search');

// Route About Us
Route::get('/about', function () {
    return view('about');
})->name('about');
