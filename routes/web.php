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
    Route::get('posisi-stock', 'BarangController@posisiStock')->name('posisi-stock');
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
    Route::get('pemakaian/addEditDetailPemakaian', 'PemakaianController@addEditDetailPemakaian');
    Route::get('pemakaian/getKode', 'PemakaianController@getKode')->name('pemakaian.get-kode');
    Route::get('pemakaian/get-detail-barang', 'PemakaianController@getDetailBarang')->name('pemakaian.get-detail-barang');
    Route::get('laporan-pemakaian', 'PemakaianController@laporan')->name('laporan-pemakaian');
    Route::get('barang-sering-dipakai', 'PemakaianController@barangSeringTerpakai')->name('barang-sering-dipakai');
    Route::get('barang-death-stock', 'PemakaianController@barangDeathStock')->name('barang-death-stock');
    Route::resource('pemakaian', 'PemakaianController');
});
Route::prefix('penjualan/')->group(function() {
    Route::get('penjualan/addDetailPenjualan', 'PenjualanController@addDetailPenjualan');
    Route::get('penjualan/getKode', 'PenjualanController@getKode')->name('penjualan.get-kode');
    Route::get('penjualan/get-detail-menu', 'PenjualanController@getDetailMenu')->name('get-detail-menu');
    Route::get('penjualan/get-diskon', 'PenjualanController@getDiskon')->name('get-diskon');
    Route::get('penjualan/filter', 'PenjualanController@filter');
    Route::get('laporan-penjualan', 'PenjualanController@laporanPenjualan')->name('laporan-penjualan');
    Route::get('menu-terlaris', 'PenjualanController@menuTerlaris')->name('menu-terlaris');
    Route::get('pembayaran', 'PenjualanController@listPembayaran')->name('pembayaran');
    Route::get('pembayaran/bayar/{kode}', 'PenjualanController@pembayaran')->name('penjualan.bayar');
    Route::put('pembayaran/save/{kode}', 'PenjualanController@savePembayaran')->name('pembayaran.save');
    Route::resource('penjualan', 'PenjualanController');
});
Route::prefix('master-menu/')->group(function(){
    Route::get('diskon/addEditDetailDiskon', 'DiskonController@addEditDetailDiskon');
    Route::get('diskon/get-detail-menu', 'DiskonController@getDetailMenu');
    Route::get('diskon/addDetailDiskon', 'DiskonController@addDetailDiskon');
    Route::get('diskon/getDetailDiskon', 'DiskonController@getDetailDiskon');
    Route::resource('diskon', 'DiskonController');
    Route::resource('kategori-menu', 'KategoriMenuController');
    Route::resource('menu', 'MenuController');
});

// kas keluar
Route::get('kas/laporan-kas', 'KasController@laporan')->name('kas.laporan');
Route::get('kas/getKode', 'KasController@getKode')->name('kas.get-kode');
Route::resource('kas', 'KasController');