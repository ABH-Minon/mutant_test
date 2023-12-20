<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

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
    return view('home');
})->name('home');;

Route::get('/register', [UserController::class, 'showRegistrationForm']);
Route::post('/register', [UserController::class, 'register']);

Route::get('/logout', [Login::class, 'logout'])->name('logout');

//Customer Panel
Route::get('/customer/dashboard', function () {
    return view('/customer/dashboard');
})->name('customer.dashboard');

Route::post('/customer/dashboard', [Login::class, 'authenticate']);
Route::get('/customer/dashboard', [ProductController::class, 'showProductsCustomer']);

//Route::post('/customer/dashboard', [UserController::class, 'showUserID']);
Route::post('/customer/cartProduct', [ProductController::class, 'cartProduct'])->middleware('auth');
Route::post('/removeOrder/{orderID}', [ProductController::class, 'removeOrder']);


Route::get('/customer/personalInfo', [UserController::class, 'showPersonalInfo']);
Route::put('/updatePersonalInfo', [UserController::class, 'updatePersonalInfo'])->name('updatePersonalInfo');

Route::get('/customer/newPassword', [UserController::class, 'getPassword'])->name('getPassword');
Route::post('/customer/newPassword', [UserController::class, 'changePassword'])->name('changePassword');

Route::get('/customer/cart', [ProductController::class, 'showCart'])->name('customer.cart');
Route::post('/checkout', [ProductController::class, 'checkout']);
Route::post('/create-checkout-session', [ProductController::class, 'createCheckoutSession'])->name('customer.create-checkout-session');

Route::get('/customer/paymentSuccess', [ProductController::class, 'paymentSuccess'])->name('paymentSuccess');
Route::get('/customer/paymentCancel', [ProductController::class, 'paymentCancel'])->name('paymentCancel');


//Admin Panel
Route::get('/admin/dashboard', function () {
    return view('/admin/dashboard');
});

Route::post('/admin/dashboard', [Login::class, 'authenticate']);

Route::get('/admin/viewUsers', function () {
    return view('/admin/viewUsers');
});

Route::get('/admin/viewProducts', [ProductController::class, 'showProducts']);
Route::post('/add-product', [ProductController::class, 'addProduct']);

Route::get('/admin/viewUsers', [UserController::class, 'showUsers']);
Route::post('/update-user-status/{userId}', [UserController::class, 'updateUserStatus']);
Route::post('/update-user-type/{userId}', [UserController::class, 'updateUserType']);
Route::post('/modify-user/{userId}', [UserController::class, 'modifyUser']);
Route::get('/get-user/{userId}', [UserController::class, 'getUser']);