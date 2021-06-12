<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('rekap-resto/{date}', 'API\MainController@getTotalByDate');

Route::get('piutang-resto/{kodePenjualan}', 'API\MainController@getPiutang');

Route::get('list-piutang-resto', 'API\MainController@getListPiutang');

Route::post('/bayar-piutang', 'App\Http\Controllers\MainController@bayarPiutang');