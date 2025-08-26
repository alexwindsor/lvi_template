<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ResetPasswordController;

// login and logout logout
Route::get('/login', function () {
    return Inertia::render('Auth/Login', [
        'email' =>  $_GET['email'] ?? null,
        'reset_link_sent' => array_key_exists('reset_link_sent', $_GET)
    ]);
})->middleware('guest')->name('login');

Route::post('/login', [UserController::class, 'login'])->middleware('guest');

Route::get('/logout', function () {
    return Inertia::render('Auth/Logout', [
        'user' => auth()->user()
    ]);
})->middleware('auth')->name('logout');

Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');


// register and verify email
Route::get('/register', function () {
    return Inertia::render('Auth/Register');
})->middleware('guest')->name('register');

Route::post('/register', [UserController::class, 'store'])->middleware('guest');

Route::get('/verify_email', function () {
    if (auth()->user()->hasVerifiedEmail()) return redirect('/');
    return Inertia::render('Auth/VerifyEmail', [
        'user' => auth()->user()
    ]);
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    if (auth()->user()->hasVerifiedEmail()) return redirect('/');
    $request->fulfill();
    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    if (auth()->user()->hasVerifiedEmail()) return redirect('/');
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// forgot password, email password reset link
Route::post('/forgot-password', [ResetPasswordController::class, 'sendEmail'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'checkTokenBeforeResetPasswordPage'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password/', [ResetPasswordController::class, 'updatePassword'])
    ->middleware('guest')
    ->name('password.update');


// edit profile
Route::get('/edit_profile', function () {
    return Inertia::render('Auth/EditProfile', [
        'user' => auth()->user()
    ]);
})->middleware('auth')->name('edit_profile');

Route::put('/update_profile', [UserController::class, 'update'])->middleware('auth');


// delete account
Route::put('/delete_account', [UserController::class, 'destroy'])->middleware('auth');
