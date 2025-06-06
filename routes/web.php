<?php

use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\account\ForgotPasswordController;
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