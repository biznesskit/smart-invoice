<?php

use App\Http\Controllers\Api\v1\Auth\RegisterTenantController;
use Illuminate\Support\Facades\Route;


Route::post('/register-tenant', [RegisterTenantController::class, 'storeTenant']);  // tobe deprecated
Route::post('/register-account', [RegisterTenantController::class, 'storeTenant']);  // tobe deprecated
Route::post('/register-company', [RegisterTenantController::class, 'storeTenant']);
Route::post('/register-factory', [RegisterTenantController::class, 'storeTenant']);

Route::post('/create-tenant-user', 'App\Http\Controllers\Api\v1\Auth\RegisterTenantController@createTenantUser'); // tobe deprecated
Route::post('/create-account-user', 'App\Http\Controllers\Api\v1\Auth\RegisterTenantController@createTenantUser');  // tobe deprecated
Route::post('/create-user', 'App\Http\Controllers\Api\v1\Auth\RegisterTenantController@createTenantUser');

Route::post('/login', 'App\Http\Controllers\Api\v1\Auth\LoginController@login')->name('login');
Route::post('/login-with-otp', 'App\Http\Controllers\Api\v1\Auth\LoginController@sendLoginWithOTPCode');
Route::post('/process-login-with-otp', 'App\Http\Controllers\Api\v1\Auth\LoginController@processLoginWithOTP');
Route::post('/forgot-business-code', 'App\Http\Controllers\Api\v1\Auth\ResetPasswordController@forgotBusinessCode')->name('forgot-business-code');
Route::post('/forgot-password', 'App\Http\Controllers\Api\v1\Auth\ResetPasswordController@forgotPassword')->name('forgot-password');
Route::post('/forgot-password-OTP-confirmation', 'App\Http\Controllers\Api\v1\Auth\ResetPasswordController@forgotPasswordOTPConfirmation')->name('reset-password.otp');
Route::post('/reset-password', 'App\Http\Controllers\Api\v1\Auth\ResetPasswordController@resetPassword')->name('reset-password');
Route::post('/resend-otp', 'App\Http\Controllers\Api\v1\Auth\ResetPasswordController@resendOTP')->name('resend-otp');



