<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'CardController@index')->name('home');

Auth::routes();
Route::get('/agreement', 'Auth\RegisterController@agreement')->name('agreement');
Route::post('/agreement', 'Auth\RegisterController@checkAgreement')->name('checkAgreement');
Route::get('/serviceAgreement', function () {
	return view('auth.serviceAgreement');
})->name('serviceAgreement');
Route::get('/personalAgreement', function () {
	return view('auth.personalAgreement');
})->name('personalAgreement');

Route::get('/cards/register', 'CardController@showRegistrationForm');
Route::post('/cards/register', 'CardController@store')->name('cards.register');
Route::get('/cards/{id}/edit', 'CardController@edit')->name('cards.edit');
Route::post('/cards/{id}/update', 'CardController@update')->name('cards.update');
Route::get('/cards/{id}/delete', 'CardController@destroy')->name('cards.delete');
Route::get('/cards/{id}', 'CardController@show')->name('cards.view');
Route::get('/card/{phone}', 'CardController@showPhone');

Route::middleware(['auth'])->group(function () {
	Route::middleware(['admin'])
		->prefix('admin')
		->group(function () {
			Route::get('/', 'AdminController@home')->name('admin.home');
		});
});