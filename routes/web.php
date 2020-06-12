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

Route::get('/','Pages@dashboard');

Route::prefix('pages/')->group(function() {
    Route::get('list-data','Pages@list');
    Route::get('form','Pages@form');
});
Route::prefix('master-barang/')->group(function() {
    Route::resource('kategori-barang', 'KategoriBarangController');
    Route::resource('barang', '');
});
Route::prefix('pembelian-barang/')->group(function() {
    Route::resource('supplier', '');
    Route::resource('pembelian', '');
    Route::resource('laporan-pembelian', '');
});
Route::prefix('pemakaian-barang/')->group(function() {
    Route::resource('pemakaian', '');
    Route::resource('laporan-pemakaian', '');
});


