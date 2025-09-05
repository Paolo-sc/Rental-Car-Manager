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
Route::get('customers', 'App\Http\Controllers\CustomerController@index')->middleware('auth')->name('customers');
Route::get('/calendar-data', 'App\Http\Controllers\CalendarDataController@index')->middleware('auth')->name('calendar.data');
Route::post('invitations', 'App\Http\Controllers\InvitationController@doInvite')->middleware('auth')->name('invite');
Route::get('register/invitation/{token}', 'App\Http\Controllers\RegisterController@showRegisterFormWithToken')->middleware('guest')->name('register.invitation');
Route::post('register/invitation/{token}', 'App\Http\Controllers\RegisterController@doRegisterWithToken')->middleware('guest')->name('register.invitation.token.post');
Route::get('invitations', 'App\Http\Controllers\InvitationController@index')->middleware('auth')->name('invitations');
Route::get('vehicles', 'App\Http\Controllers\VehicleController@index')->middleware('auth')->name('vehicles.index');
Route::delete('vehicles/delete/{id}', 'App\Http\Controllers\VehicleController@delete')->middleware('auth')->name('vehicles.delete');
Route::get('vehicles/get/{status}', 'App\Http\Controllers\VehicleController@getVehicles')->middleware('auth')->name('vehicles.get');
Route::post('vehicles', 'App\Http\Controllers\VehicleController@addVehicle')->middleware('auth')->name('vehicles.add');
Route::get('vehicles/getById/{id}', 'App\Http\Controllers\VehicleController@getVehicleById')->middleware('auth')->name('vehicles.getById');
Route::put('vehicles/update/{id}', 'App\Http\Controllers\VehicleController@updateVehicle')->middleware('auth')->name('vehicles.update');
Route::post('customers', 'App\Http\Controllers\CustomerController@addCustomer')->middleware('auth')->name('customers.add');
Route::get('customers/get/{filter}', 'App\Http\Controllers\CustomerController@getCustomers')->middleware('auth')->name('customers.get');
Route::delete('customers/delete/{id}', 'App\Http\Controllers\CustomerController@delete')->middleware('auth')->name('customers.delete');
Route::get('/oauth/google/popup', 'App\Http\Controllers\GoogleDriveController@redirectToGooglePopup')->name('google.drive.auth.popup')->middleware('auth');
Route::get('/oauth/google/callback-popup', 'App\Http\Controllers\GoogleDriveController@handleGoogleCallbackPopup')->name('google.drive.callback.popup')->middleware('auth');
Route::post('/salva-token-google-drive', 'App\Http\Controllers\GoogleDriveController@salvaTokenGoogleDrive')->name('google.drive.save')->middleware('auth');
Route::get('/drive-upload', 'App\Http\Controllers\GoogleDriveUploadController@showUploadForm')->name('drive.upload')->middleware('auth');
Route::post('/drive-upload', 'App\Http\Controllers\GoogleDriveUploadController@handleUpload')->name('drive.upload.handle')->middleware('auth');
Route::get('customers/{id}/documents', 'App\Http\Controllers\CustomerController@getDocumentsByCustomerId')->middleware('auth')->name('customers.getDocuments');
Route::delete('customers/{customerId}/documents/{documentId}', 'App\Http\Controllers\CustomerController@deleteDocument')->middleware('auth')->name('customers.deleteDocument');
