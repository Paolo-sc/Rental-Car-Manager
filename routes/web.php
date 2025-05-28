<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('login');
});

Route::get('register', 'App\Http\Controllers\LoginController@showRegistrationForm');
Route::post('register', 'App\Http\Controllers\LoginController@doRegister');
Route::get('login', 'App\Http\Controllers\LoginController@showLoginForm')->name('login');
Route::post('login', 'App\Http\Controllers\LoginController@doLogin');
Route::get('dashboard', 'App\Http\Controllers\LoginController@dashboard')->middleware('auth')->name('dashboard');
Route::get('logout', 'App\Http\Controllers\LoginController@doLogout')->middleware('auth')->name('logout');