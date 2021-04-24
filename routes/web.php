<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});



Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {

	Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');

	Route::group(['middleware' => ['XssSanitizer']], function () {
		Route::get('/students','App\Http\Controllers\StudentsController@index')->name('students');
		Route::post('/add-student','App\Http\Controllers\StudentsController@store')->name('add-student');
		Route::get('/student-records', 'App\Http\Controllers\StudentsController@studentRecords')->name('student-records');

		Route::post('/fetch-states','App\Http\Controllers\StudentsController@fetchStateData')->name('fetch-states');
		Route::post('/fetch-cities','App\Http\Controllers\StudentsController@fetchCityData')->name('fetch-cities');
	});	
});

Route::fallback( function () {
    abort( 404 );
} );