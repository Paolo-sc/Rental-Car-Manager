<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('login');
});

Route::get('login', 'App\Http\Controllers\LoginController@showLoginForm')->middleware('guest')->name('login');
Route::post('login', 'App\Http\Controllers\LoginController@doLogin')->middleware('guest');
Route::get('dashboard', 'App\Http\Controllers\DashboardController@showDashboard')->middleware('auth')->name('dashboard');
Route::get('logout', 'App\Http\Controllers\LoginController@doLogout')->middleware('auth')->name('logout');
Route::get('documents', 'App\Http\Controllers\DocumentController@showDocuments')->middleware('auth')->name('documents');
Route::get('customers', 'App\Http\Controllers\DocumentController@showDocuments')->middleware('auth')->name('customers');
Route::get('/calendar-data', 'App\Http\Controllers\CalendarDataController@index')->middleware('auth')->name('calendar.data');
Route::post('invitations', 'App\Http\Controllers\InvitationController@doInvite')->middleware('auth')->name('invite');
Route::get('register/invitation/{token}', 'App\Http\Controllers\RegisterController@showRegisterFormWithToken')->middleware('guest')->name('register.invitation');
Route::post('register/invitation/{token}', 'App\Http\Controllers\RegisterController@doRegisterWithToken')->middleware('guest')->name('register.invitation.token.post');
Route::get('invitations', 'App\Http\Controllers\InvitationController@index')->middleware('auth')->name('invitations');
Route::get('vehicles', 'App\Http\Controllers\VehicleController@index')->middleware('auth')->name('vehicles.index');
Route::delete('vehicles/delete/{id}', 'App\Http\Controllers\VehicleController@delete')->middleware('auth')->name('vehicles.delete');
Route::get('vehicles/get/{status}', 'App\Http\Controllers\VehicleController@getVehicles')->middleware('auth')->name('vehicles.get');
Route::post('vehicles', 'App\Http\Controllers\VehicleController@addVehicle')->middleware('auth')->name('vehicles.add');
