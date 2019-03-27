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

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::group(['prefix' => 'auth'], function() {
	Route::post('check', 'HakAksesController@check');
	Route::post('logout', 'HakAksesController@logout');
});

Route::group(['prefix' => 'petugas'], function() {
	Route::get('/', 'PetugasController@index')->name('petugas.index');
	Route::get('/tambah', 'PetugasController@create')->name('petugas.tambah');
	Route::post('/save', 'PetugasController@save')->name('petugas.save');
	Route::get('/{id}/edit', 'PetugasController@edit')->name('petugas.edit');
	Route::post('/update', 'PetugasController@update')->name('petugas.update');
	Route::post('/delete', 'PetugasController@delete')->name('petugas.delete');
	Route::get('dtIndex', 'PetugasController@dtIndex')->name('petugas.dtIndex');
});

Route::group(['prefix' => 'layanan'], function() {
	Route::get('/', 'LayananController@index')->name('layanan.index');
	Route::get('/tambah', 'LayananController@create')->name('layanan.tambah');
	Route::post('/save', 'LayananController@save')->name('layanan.save');
	Route::get('/{id}/edit', 'LayananController@edit')->name('layanan.edit');
	Route::post('/update', 'LayananController@update')->name('layanan.update');
	Route::post('/delete', 'LayananController@delete')->name('layanan.delete');
	Route::get('dtIndex', 'LayananController@dtIndex')->name('layanan.dtIndex');
});

Route::group(['prefix' => 'warga'], function() {
	Route::get('/', 'WargaController@index')->name('warga.index');
	Route::get('/tambah', 'WargaController@create')->name('warga.tambah');
	Route::post('/save', 'WargaController@save')->name('warga.save');
	Route::get('/{id}/edit', 'WargaController@edit')->name('warga.edit');
	Route::post('/detail', 'WargaController@detail')->name('warga.detail');
	Route::post('/update', 'WargaController@update')->name('warga.update');
	Route::post('/delete', 'WargaController@delete')->name('warga.delete');
	Route::get('dtIndex', 'WargaController@dtIndex')->name('warga.dtIndex');
});

Route::group(['prefix' => 'permohonan'], function() {
	Route::get('/', 'PermohonanController@index')->name('permohonan.index');
	Route::get('/tambah', 'PermohonanController@create')->name('permohonan.tambah');
	Route::post('/save', 'PermohonanController@save')->name('permohonan.save');
	Route::get('/{id}/edit', 'PermohonanController@edit')->name('permohonan.edit');
	Route::post('/update', 'PermohonanController@update')->name('permohonan.update');
	Route::post('/delete', 'PermohonanController@delete')->name('permohonan.delete');
	Route::get('dtIndex', 'PermohonanController@dtIndex')->name('permohonan.dtIndex');
	Route::post('preview', 'PermohonanController@preview')->name('permohonan.preview');
	Route::get('stats', 'PermohonanController@stats')->name('permohonan.stats');
});

Route::group(['prefix' => 'pengaduan'], function() {
	Route::get('/', 'PengaduanController@index')->name('pengaduan.index');
	Route::get('/tambah', 'PengaduanController@create')->name('pengaduan.tambah');
	Route::post('/save', 'PengaduanController@save')->name('pengaduan.save');
	Route::get('/{id}/detail', 'PengaduanController@detail')->name('pengaduan.detail');
	Route::post('/update', 'PengaduanController@update')->name('pengaduan.update');
	Route::post('/delete', 'PengaduanController@delete')->name('pengaduan.delete');
	Route::get('dtIndex', 'PengaduanController@dtIndex')->name('pengaduan.dtIndex');
	Route::post('komentar', 'PengaduanController@komentar')->name('pengaduan.komentar');
	Route::post('close', 'PengaduanController@close')->name('pengaduan.close');
});

Route::group(['prefix' => 'arsip'], function() {
	Route::get('/', 'ArsipController@index')->name('arsip.index');
	Route::get('/tambah', 'ArsipController@create')->name('arsip.tambah');
	Route::post('/save', 'ArsipController@save')->name('arsip.save');
	Route::post('/update', 'ArsipController@update')->name('arsip.update');
	Route::post('/delete', 'ArsipController@delete')->name('arsip.delete');
	Route::get('dtIndex', 'ArsipController@dtIndex')->name('arsip.dtIndex');
	Route::post('download', 'ArsipController@download')->name('arsip.download');
});

Route::group(['prefix' => 'verifikasi'], function() {
	Route::get('/', 'VerifikasiController@index');
	Route::get('dtIndex', 'VerifikasiController@dtIndex')->name('verifikasi.dtIndex');
	Route::post('verify', 'VerifikasiController@verify')->name('verifikasi.verify');
});
