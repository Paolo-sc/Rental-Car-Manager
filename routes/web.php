<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('login');
});

Route::get('login', 'App\Http\Controllers\LoginController@showLoginForm')->name('login');
Route::post('login', 'App\Http\Controllers\LoginController@doLogin');
Route::get('dashboard', 'App\Http\Controllers\DashboardController@showDashboard')->middleware('auth')->name('dashboard');
Route::get('logout', 'App\Http\Controllers\LoginController@doLogout')->middleware('auth')->name('logout');
Route::get('documents', 'App\Http\Controllers\DocumentController@showDocuments')->middleware('auth')->name('documents');
Route::get('customers', 'App\Http\Controllers\DocumentController@showDocuments')->middleware('auth')->name('customers');
Route::get('/calendar-data', 'App\Http\Controllers\CalendarDataController@index')->name('calendar.data');
Route::post('documents', 'App\Http\Controllers\InvitationController@doInvite')->name('invite');
Route::get('register/invitation/{token}', 'App\Http\Controllers\RegisterController@showRegisterFormWithToken')->name('register.invitation');
Route::post('register/invitation', 'App\Http\Controllers\RegisterController@doRegisterWithToken')->name('register.invitation.post');
Route::get('invitations', 'App\Http\Controllers\InvitationController@index')->middleware('auth')->name('invitations');