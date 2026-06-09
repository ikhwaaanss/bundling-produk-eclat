<?php

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('/register', 'Auth\RegisterController@register');

Route::middleware('auth')->group(function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    
    Route::resource('produk', 'ProdukController');
    Route::resource('transaksi', 'TransaksiController');
    Route::delete('transaksi/{id}/batalkan', 'TransaksiController@destroy')->name('transaksi.batalkan');
    
    Route::group(['prefix' => 'analisis', 'as' => 'analisis.'], function () {
        Route::get('/', 'AnalisisBundlingController@index')->name('index');
        Route::get('konfigurasi', 'AnalisisBundlingController@konfigurasi')->name('konfigurasi');
        Route::post('jalankan', 'AnalisisBundlingController@jalankan')->name('jalankan');
        Route::get('detail/{id}', 'AnalisisBundlingController@detail')->name('detail');
    });
    
    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
        Route::resource('user', 'UserController');
        Route::resource('activitylog', 'ActivityLogController');
    });
});
