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
    Route::resource('barang', 'BarangController');
    Route::get('barang-minimum', 'BarangController@barangMinimum')->name('barang-minimum');
});
Route::prefix('pembelian-barang/')->group(function() {
    Route::resource('supplier', 'SupplierController');
    Route::get('pembelian/addDetailPembelian', 'PembelianController@addDetailPembelian');
    Route::get('pembelian/addEditDetailPembelian', 'PembelianController@addEditDetailPembelian');
    Route::get('pembelian/getKode', 'PembelianController@getKode');
    Route::get('laporan-pembelian', 'PembelianController@laporan');
    Route::resource('pembelian', 'PembelianController');
});
Route::prefix('pemakaian-barang/')->group(function() {
    Route::get('pemakaian/addDetailPemakaian', 'PemakaianController@addDetailPemakaian');
    Route::get('pemakaian/getKode', 'PemakaianController@getKode')->name('pemakaian.get-kode');
    Route::get('pemakaian/get-detail-barang', 'PemakaianController@getDetailBarang')->name('pemakaian.get-detail-barang');
    Route::resource('pemakaian', 'PemakaianController');
    Route::get('laporan-pemakaian', 'PemakaianController@laporan')->name('laporan-pemakaian');
});
Route::prefix('master-menu/')->group(function(){
    Route::resource('kategori-menu', 'KategoriMenuController');
    Route::resource('menu', 'MenuController');
});

// kas keluar
Route::get('kas-keluar/laporan-kas-keluar', 'KasKeluarController@laporan')->name('kas-keluar.laporan');
Route::get('kas-keluar/getKode', 'KasKeluarController@getKode')->name('kas-keluar.get-kode');
Route::resource('kas-keluar', 'KasKeluarController');


