<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;

//  Root redirect to login
Route::get('/', fn() => redirect()->route('login'));

// Auth routes
Route::get('/register',[RegisterController::class, 'showForm'])->name('register');
Route::post('/register',[RegisterController::class, 'register']);

Route::get('/login',[LoginController::class, 'showForm'])->name('login');
Route::post('/login',[LoginController::class, 'login']);
Route::post('/logout',[LoginController::class, 'logout'])->name('logout');

Route::get('/forgot-password',[ForgotPasswordController::class, 'showForm'])->name('forgot.password');
Route::post('/forgot-password', [ForgotPasswordController::class, 'send']);

Route::get('/reset-password/{token}',[ResetPasswordController::class, 'showForm'])->name('reset.password');
Route::post('/reset-password/{token}',[ResetPasswordController::class, 'reset']);

// Normal user (logged in)
Route::middleware(['auth.check'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

// Admin onlyse
Route::middleware(['auth.check', 'is.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard',[DashboardController::class, 'index'])->name('dashboard');
    Route::get('/users',[UserController::class, 'index'])->name('users');
    Route::get('/users/create',[UserController::class, 'create'])->name('users.create');
    Route::post('/users',[UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit',[UserController::class, 'edit'])->name('users.edit');
    Route::post('/users/{id}',[UserController::class, 'update'])->name('users.update');
    Route::post('/users/{id}/delete', [UserController::class, 'destroy'])->name('users.destroy');
});


