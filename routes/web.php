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
Auth::routes();
Route::group(['middleware' => ['auth']], function () {

    Route::get('user/ganti-password/', 'UserController@gantiPassword')->name('user.ganti-password');

    Route::put('user/update-password/{id}', 'UserController@updatePassword')->name('user.update-password');

    Route::get('home','Pages@dashboard')->name('dashboard');

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
    Route::prefix('laporan/')->group(function() {
        Route::get('laba-rugi', 'LabaRugiController@index')->name('laba-rugi');
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
        Route::get('{kode}/edit', 'PenjualanController@edit')->name('edit-penjualan');
        Route::get('penjualan/addDetailPenjualan', 'PenjualanController@addDetailPenjualan');
        Route::get('penjualan/getKode', 'PenjualanController@getKode')->name('penjualan.get-kode');
        Route::get('penjualan/get-detail-menu', 'PenjualanController@getDetailMenu')->name('get-detail-menu');
        Route::get('penjualan/menu-bill', 'PenjualanController@menuBill')->name('menu-bill');
        Route::get('penjualan/get-diskon', 'PenjualanController@getDiskon')->name('get-diskon');
        Route::get('penjualan/filter', 'PenjualanController@filter');
        Route::get('penjualan/all', 'PenjualanController@allPenjualan')->name('penjualan.all');
        Route::get('penjualan/soft-delete/{kode}', 'PenjualanController@softDelete')->name('penjualan.soft-delete');
        Route::get('penjualan/restore/{kode}', 'PenjualanController@restoreData')->name('penjualan.restore');
        Route::get('laporan-penjualan', 'PenjualanController@laporanPenjualan')->name('laporan-penjualan');
        Route::get('laporan-penjualan/print', 'PenjualanController@printLaporanPenjualan')->name('laporan-penjualan.print');
        Route::get('menu-terlaris', 'PenjualanController@menuTerlaris')->name('menu-terlaris');
        Route::get('menu-terlaris/print', 'PenjualanController@printMenuTerlaris')->name('print-menu-terlaris');
        Route::get('menu-paling-menghasilkan', 'PenjualanController@menuPalingMenghasilkan')->name('menu-paling-menghasilkan');
        Route::get('menu-paling-menghasilkan/print', 'PenjualanController@printMenuPalingMenghasilkan')->name('print-menu-paling-menghasilkan');
        Route::get('pembayaran', 'PenjualanController@listPembayaran')->name('pembayaran');
        Route::get('pembayaran/{kode}', 'PenjualanController@pembayaran')->name('penjualan.bayar');
        Route::get('cetak-bill/{kode}', 'PenjualanController@cetakBill')->name('cetak-bill');
        Route::get('cetak-dapur/{kode}', 'PenjualanController@cetakDapur')->name('cetak-dapur');
        Route::put('pembayaran/save/{kode}', 'PenjualanController@savePembayaran')->name('pembayaran.save');
        Route::get('piutang/change/{kode}', 'PenjualanController@changeToPiutang')->name('piutang.change-to-piutang');
        Route::resource('penjualan', 'PenjualanController');
    });
    Route::prefix('piutang/')->group(function() {
        Route::resource('piutang', 'PiutangController');
    });
    Route::prefix('master-menu/')->group(function(){
        Route::get('diskon/addEditDetailDiskon', 'DiskonController@addEditDetailDiskon');
        Route::get('diskon/get-detail-menu', 'DiskonController@getDetailMenu');
        Route::get('diskon/addDetailDiskon', 'DiskonController@addDetailDiskon');
        Route::get('diskon/getDetailDiskon', 'DiskonController@getDetailDiskon');
        Route::patch('menu/{id}/activate', 'MenuController@activateMenu')->name('menu.activate');
        Route::resource('diskon', 'DiskonController');
        Route::resource('kategori-menu', 'KategoriMenuController');
        Route::resource('menu', 'MenuController');
    });

    // kas keluar
    Route::get('kas/laporan-kas', 'KasController@laporan')->name('kas.laporan');
    Route::get('kas/getKode', 'KasController@getKode')->name('kas.get-kode');
    Route::resource('kas', 'KasController');

    Route::get('/home', 'HomeController@index')->name('home');

    Route::resource('user', 'UserController');
});