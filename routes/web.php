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
Route::post('documents', 'App\Http\Controllers\CustomerController@addDocument')->middleware('auth')->name('documents.add');
Route::get('documents/{id}', 'App\Http\Controllers\CustomerController@getDocumentById')->middleware('auth')->name('documents.getById');
Route::put('documents/update/{id}', 'App\Http\Controllers\CustomerController@updateDocument')->middleware('auth')->name('documents.update');
Route::get('customer/{id}', 'App\Http\Controllers\CustomerController@getCustomerById')->middleware('auth')->name('customers.getById');
Route::put('customers/update/{id}', 'App\Http\Controllers\CustomerController@updateCustomer')->middleware('auth')->name('customers.update');
Route::get('vehicles/{id}/documents', 'App\Http\Controllers\VehicleController@getDocumentsByVehicleId')->middleware('auth')->name('vehicles.getDocuments');
Route::post('vehicles/add-document', 'App\Http\Controllers\VehicleController@addDocument')->middleware('auth')->name('vehicles.addDocument');
Route::delete('vehicles/{vehicleId}/documents/{documentId}', 'App\Http\Controllers\VehicleController@deleteDocument')->middleware('auth')->name('vehicles.deleteDocument');
Route::put('vehicles/documents/update/{id}', 'App\Http\Controllers\VehicleController@updateDocument')->middleware('auth')->name('vehicles.documents.update');
Route::get('vehicles/documents/{id}', 'App\Http\Controllers\VehicleController@getDocumentById')->middleware('auth')->name('vehicles.documents.getById');
Route::get('reservations', 'App\Http\Controllers\ReservationController@showReservation')->middleware('auth')->name('reservations');
Route::get('reservations/get', 'App\Http\Controllers\ReservationController@getReservation')->middleware('auth')->name('reservations.get');
Route::delete('reservations/delete/{id}', 'App\Http\Controllers\ReservationController@delete')->middleware('auth')->name('reservations.delete');
Route::get('customers/search', 'App\Http\Controllers\CustomerController@search')->middleware('auth')->name('customers.search');
Route::get('vehicles/search', 'App\Http\Controllers\VehicleController@search')->middleware('auth')->name('vehicles.search');
Route::get('drivers', 'App\Http\Controllers\DriverController@index')->middleware('auth')->name('drivers.index');
Route::get('drivers/search', 'App\Http\Controllers\DriverController@search')->middleware('auth')->name('drivers.search');
Route::post('reservations/add', 'App\Http\Controllers\ReservationController@addReservation')->middleware('auth')->name('reservations.add');