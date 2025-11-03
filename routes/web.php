<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Api\allDataController;
use App\Http\Controllers\admin\CustomerController;
use App\Http\Controllers\admin\ProductsController;
use App\Http\Controllers\admin\SettingsController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\TransactionController;

//UTAMA
Route::get('/', [HomeController::class, 'index']);
Route::get('/products', [HomeController::class, 'products']);
Route::get('/categories', [HomeController::class, 'categories']);
Route::get('/promo', [HomeController::class, 'promo']);
Route::get('/details/{product_code}/{product_slug}', [HomeController::class, 'details']);
Route::get('/cart', [HomeController::class, 'cart']);
Route::get('/checkout', [HomeController::class, 'checkout']);
Route::get('/login', [AuthController::class, 'index']);
Route::post('/login/process', [AuthController::class, 'login']);
Route::post('/register/process', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/forgot-password/process', [AuthController::class, 'sendResetLink']);
Route::get('/reset-password/{token}', [AuthController::class, 'resetPassword']);
Route::post('/reset-password/process', [AuthController::class, 'updatePassword']);
Route::get('/my', [HomeController::class, 'my']);

//API
// ===================================================================================

//CUSTOMER
Route::get('/api/art/products/all', [allDataController::class, 'allProducts']);
Route::get('/api/art/products/category/all', [allDataController::class, 'allProductCategory']);
Route::get('/api/art/products/category/{id_kategori}', [allDataController::class, 'productsByCategory']);
Route::get('/api/art/products/popular/all', [allDataController::class, 'allPopularProduct']);
Route::get('/api/art/related/{productCode}/{product_slug}', [allDataController::class, 'relatedProducts']);
Route::get('/api/art/details/{productCode}/{product_slug}', [allDataController::class, 'details']);
Route::get('/api/details/image/{slug}', [allDataController::class, 'productImages']);
Route::get('/api/art/products/search/{name}', [allDataController::class, 'searchProductByName']);

//ADMIN
Route::post('/api/art/category/product/add/process', [allDataController::class, 'addProductCategory']);
Route::post('/api/art/products/add/process', [allDataController::class, 'addProduct']);
Route::match(['POST', 'PUT'], '/api/art/products/edit/{id_produk}/process', [allDataController::class, 'editProduct']);
Route::post('/api/art/products/del/{id_produk}/process', [allDataController::class, 'deleteProduct']);
Route::post('/api/art/products/category/edit/{id_kategori}/process', [allDataController::class, 'editProductCategory']);
Route::post('/api/art/products/category/del/{id_produk}/process', [allDataController::class, 'deleteProductCategory']);
Route::get('/api/art/transactions/all', [allDataController::class, 'allTransactions']);
Route::get('/api/art/transactions/{trCode}', [allDataController::class, 'allTransactionsByTransCode']);

//USER
Route::post('/api/user/profile/update', [allDataController::class, 'updateProfile']);
Route::post('/api/user/password/update', [allDataController::class, 'updatePassword']);
Route::get('/api/user/check-login', [allDataController::class, 'checkLogin']);
Route::get('/api/user/profile', [allDataController::class, 'getUserProfile']);
Route::post('/api/cart/add', [allDataController::class, 'addToCart']);
Route::get('/api/cart/all', [allDataController::class, 'getCart']);
Route::post('/api/cart/update', [allDataController::class, 'updateCart']);
Route::post('/api/cart/remove', [allDataController::class, 'removeFromCart']);
Route::get('/api/users/address/{id_user}/all', [allDataController::class, 'addressByUser']);
Route::post('/api/users/address/add', [allDataController::class, 'addAddress']);
Route::post('/api/users/address/set-primary', [allDataController::class, 'setPrimaryAddress']);
Route::post('/api/shipping/calculate-cost', [allDataController::class, 'calculateShippingCost']);
Route::get('/api/test/shipping', [allDataController::class, 'testShippingSystem']);
Route::post('/api/customer/pay/add', [allDataController::class, 'uploadPaymentProof']);
Route::get('/api/user/data', [allDataController::class, 'getUserData']);
Route::post('/api/cust/transactions/create', [allDataController::class, 'createTransactions']);

//REGIONS
Route::get('/api/regions/provinces', [allDataController::class, 'getProvinces']);
Route::get('/api/regions/regencies/{provinceId}', [allDataController::class, 'getRegencies']);
Route::get('/api/regions/districts/{regencyId}', [allDataController::class, 'getDistricts']);

//ADMIN
Route::get('/api/admin/dashboard/stats', [allDataController::class, 'dashboardStats']);
//ADMIN SETTINGS
Route::get('/api/admin/profile', [allDataController::class, 'getAdminProfile']);
Route::post('/api/admin/profile/update', [allDataController::class, 'updateAdminProfile']);
Route::post('/api/admin/password/update', [allDataController::class, 'updateAdminPassword']);
Route::get('/api/admin/bank-accounts', [allDataController::class, 'getBankAccounts']);
Route::get('/api/admin/bank-accounts/active', [allDataController::class, 'getBankAccountsActive']);
Route::post('/api/admin/bank-accounts/add', [allDataController::class, 'addBankAccount']);
Route::delete('/api/admin/bank-accounts/delete/{id}', [allDataController::class, 'deleteBankAccount']);
Route::post('/api/admin/bank/active/{id}', [allDataController::class, 'bankActive']);
Route::get('/api/admin/bank-accounts/{id}', [allDataController::class, 'bankEdit']);
Route::post('/api/admin/bank-accounts/update/{id}', [allDataController::class, 'editBankAccount']);

//ADMIN SETTINGS PAGE
Route::post('/api/admin/verify-payment', [allDataController::class, 'verifyPayment']);
Route::post('/api/admin/update-shipping-status', [allDataController::class, 'updateShippingStatus']);
Route::get('/api/admin/transactions/export/excel', [allDataController::class, 'exportTransactionsExcel']);
Route::get('/api/admin/transactions/export/pdf', [allDataController::class, 'exportTransactionsPdf']);
Route::get('/api/admin/customers/all', [allDataController::class, 'allCustomers']);
Route::prefix('admin')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('AdminLogin');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/products', [ProductsController::class, 'index'])->name('products');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
});
