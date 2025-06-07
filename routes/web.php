<?php

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\account\ForgotPasswordController;
use App\Http\Controllers\Admin\AdminSiderbarController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\admin\CustomerController;
use App\Http\Controllers\Admin\SlideController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\FrontEnd\AccountController as FrontEndAccountController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\frontend\HomeController;
use Illuminate\Support\Facades\Route;
use UniSharp\LaravelFilemanager\Lfm;

Route::get('/',[HomeController::class, "index"]);

Route::group(['prefix' => 'files-manager', 'middleware' => ['web', 'auth']], function () {
    Lfm::routes();
});

//Account
Route::get('/login', [AccountController::class, 'login'])->name('login');
Route::post('/login', [AccountController::class, 'postLogin'])->name('postLogin');
Route::get('/register', [AccountController::class, 'register'])->name('register');
Route::post('/register', [AccountController::class, 'postRegister'])->name('postRegister');
Route::get('/logout', [AccountController::class, 'logout'])->name('logout');

//Reset Password
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/new-password', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');


Route::prefix('admin')->middleware("admin")->group(function () {

    //Profile
    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
    Route::get('/edit-profile', [AccountController::class, 'editProfile'])->name('edit-profile');
    Route::post('/profile/update', [AccountController::class, 'updateProfile'])->name('updateProfile');
    Route::get('/change-password', [AccountController::class, 'editPassword'])->name('editPassword');
    Route::post('/update-password', [AccountController::class, 'updatePassword'])->name('updatePassword');

    //Admin Sidebar
    Route::prefix('admin-sidebar')->group(function () {
        Route::get('/index', [AdminSiderbarController::class, 'index'])->name('admin-sidebar.index');
        Route::get('/create', [AdminSiderbarController::class, 'create'])->name('admin-sidebar.create');
        Route::post('/store', [AdminSiderbarController::class, 'store'])->name('admin-sidebar.store');
        Route::get('/edit/{id}', [AdminSiderbarController::class, 'edit'])->name('admin-sidebar.edit');
        Route::post('/update/{id}', [AdminSiderbarController::class, 'update'])->name('admin-sidebar.update');
        Route::delete('/destroy/{id}', [AdminSiderbarController::class, 'destroy']);
        Route::post('/change/{id}', [AdminSiderbarController::class, 'changeActive']);
    });

    //Slide
    Route::prefix('slide')->group(function () {
        Route::get('/index', [SlideController::class, 'index'])->name('slide.index');
        Route::get('/create', [SlideController::class, 'create'])->name('slide.create');
        Route::post('/store', [SlideController::class, 'store'])->name('slide.store');
        Route::get('/edit/{id}', [SlideController::class, 'edit'])->name('slide.edit');
        Route::post('/update/{id}', [SlideController::class, 'update'])->name('slide.update');
        Route::delete('/destroy/{id}', [SlideController::class, 'destroy']);
        Route::post('/change/{id}', [SlideController::class, 'changeActive']);
    });

    //Admin User
    Route::prefix('user')->group(function () {
        Route::get('/index', [UserController::class, 'index'])->name('user.index');
        Route::get('/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/show/{id}', [UserController::class, 'show'])->name("user.show");
    });

    //Admin Customer
    Route::prefix('customer')->group(function () {
        Route::get('/index', [CustomerController::class, 'index'])->name('customer.index');
        Route::get('/create', [CustomerController::class, 'create'])->name('customer.create');
        Route::post('/store', [CustomerController::class, 'store'])->name('customer.store');
        Route::get('/edit/{id}', [CustomerController::class, 'edit'])->name('customer.edit');
        Route::post('/update/{id}', [CustomerController::class, 'update'])->name('customer.update');
        Route::post('/change/{id}', [CustomerController::class, 'changeStatus']);
        Route::delete('/destroy/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');
    });

    // Admin Voucher
    Route::prefix('voucher')->group(function () {
        Route::get('/index', [VoucherController::class, 'index'])->name('voucher.index');
        Route::get('/create', [VoucherController::class, 'create'])->name('voucher.create');
        Route::post('/store', [VoucherController::class, 'store'])->name('voucher.store');
        Route::get('/edit/{id}', [VoucherController::class, 'edit'])->name('voucher.edit');
        Route::post('/update/{id}', [VoucherController::class, 'update'])->name('voucher.update');
        Route::post('/change/{id}', [VoucherController::class, 'changeStatus'])->name('voucher.changeStatus');
        Route::delete('/destroy/{id}', [VoucherController::class, 'destroy'])->name('voucher.destroy');
    });

    //Admin Contact
    Route::prefix('contact')->group(function () {
        Route::get('/index', [AdminContactController::class, 'index'])->name('contact.index');
        Route::get('/{id}', [AdminContactController::class, 'show'])->name('contact.show');
        Route::delete('/{id}', [AdminContactController::class, 'destroy'])->name('contact.destroy');
        Route::post('/mark-read/{id}', [AdminContactController::class, 'markAsRead'])->name('contact.mark-read');
        Route::post('/mark-unread/{id}', [AdminContactController::class, 'markAsUnread'])->name('contact.mark-unread');
        Route::post('/{id}/reply', [AdminContactController::class, 'reply'])->name('contact.reply');
    });

    //Admin Brand
    Route::prefix('brand')->group(function () {
        Route::get('/index', [BrandController::class, 'index'])->name('brand.index');
        Route::get('/', [BrandController::class, 'index']);
        Route::get('/create', [BrandController::class, 'create'])->name('brand.create');
        Route::post('/store', [BrandController::class, 'store'])->name('brand.store'); 
        Route::get('/edit/{id}', [BrandController::class, 'edit'])->name('brand.edit');
        Route::post('/update/{id}', [BrandController::class, 'update'])->name('brand.update');
        Route::post('/change/{id}', [BrandController::class, 'changeActive'])->name('brand.change');
        Route::delete('/destroy/{id}', [BrandController::class, 'destroy'])->name('brand.destroy');
    });

    //Admin Brand
    Route::prefix('category')->group(function () {
        Route::get('/index', [CategoryController::class, 'index'])->name('category.index');
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/create', [CategoryController::class, 'create'])->name('category.create');
        Route::post('/store', [CategoryController::class, 'store'])->name('category.store'); 
        Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
        Route::post('/update/{id}', [CategoryController::class, 'update'])->name('category.update');
        Route::post('/change/{id}', [CategoryController::class, 'changeActive'])->name('category.change');
        Route::delete('/destroy/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
    });
});

//Frontend
Route::get('/home', [HomeController::class, 'index'])->name('frontend.home.index');
Route::get('/contact', [ContactController::class, 'index'])->name(name: 'frontend.contact.index');
Route::post('/contact/send', [ContactController::class, 'sendContact'])->middleware('auth')->name('frontend.contact.send');

Route::prefix('account')->middleware("auth")->group(function () {
    Route::get('/profile', [FrontEndAccountController::class, 'profile'])->name('frontend.profile');
    Route::get('/edit-profile', [FrontEndAccountController::class, 'editProfile'])->name('frontend.edit-profile');
    Route::post('/update-profile', [FrontEndAccountController::class, 'updateProfile'])->name('frontend.update-profile');
    Route::get('/change-password', [FrontEndAccountController::class, 'editPassword'])->name('frontend.edit-password');
    Route::post('/update-password', [FrontEndAccountController::class, 'updatePassword'])->name('frontend.update-password');
});