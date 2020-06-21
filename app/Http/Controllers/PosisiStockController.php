<?php

namespace App\Http\Controllers;

use App\Barang;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Pembelian;
use \App\Pemakaian;
use \App\DetailPembelian;
use \App\DetailPemakaian;

class PosisiStockController extends Controller
{
    public function index(Request $request)
    {
        $barang = Barang::where('stock', '>', 0)->get();

        
    }
}
