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

Route::get('/', 'CardController@index');

Auth::routes();


Route::get('/cards/register', 'CardController@showRegistrationForm')->name('cards.register');


//Route::get('/cards/files/quick-edit/images/', 'CardController@quickEditImage');
//Route::get('/cards/files/quick-edit/images/{fileName?}', 'CardController@quickEditImage');
//Request URL: http://zname.test/cards/files/quick-edit/images/1ZzVghqfL9UfKyeVYcKJniQUGm2AE4EqojKD1vHYB.jpeg

Route::get('/cards/files/quick-edit/images/', 'CardController@quickEditImage');
Route::post('/cards/files/quick-edit/update', 'CardController@quickEditImageUpload');
Route::delete('/cards/files/quick-edit/delete', 'CardController@quickEditImageDelete');