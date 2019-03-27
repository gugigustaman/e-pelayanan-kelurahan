<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'api'], function() {
	Route::group(['prefix' => 'warga'], function() {
		Route::post('register', 'WargaController@apiRegister');
		Route::post('login', 'WargaController@apiLogin');
		Route::post('check', 'WargaController@apiCheck');
		Route::post('logout', 'WargaController@apiLogout');
	});

	Route::group(['prefix' => 'layanan'], function() {
		Route::post('index', 'LayananController@apiIndex');
		Route::post('detail', 'LayananController@apiDetail');
	});

	Route::group(['prefix' => 'permohonan'], function() {
		Route::post('index', 'PermohonanController@apiIndex');
		Route::post('create', 'PermohonanController@apiCreate');
		Route::post('new_number', 'PermohonanController@apiNewNumber');
	});

	Route::group(['prefix' => 'pengaduan'], function() {
		Route::post('index', 'PengaduanController@apiIndex');
		Route::post('create', 'PengaduanController@apiCreate');
		Route::post('new_number', 'PengaduanController@apiNewNumber');
		Route::post('detail', 'PengaduanController@apiDetail');
		Route::post('new_comment', 'PengaduanController@apiNewComment');
	});
});
