<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Penjualan;
use \App\Pemakaian;
use \App\Kas;

class LabaRugiController extends Controller
{
    private $param;
    public function __construct()
    {
        $this->param['icon'] = 'ni-archive-2 text-purple';
    }

    public function index(Request $request)
    {
        $bulan = $request->get('bulan');
        $tahun = $request->get('tahun');

        $totalPenjualan = Penjualan::select(\DB::raw('SUM(total_harga) as ttlHarga'), \DB::raw('SUM(total_diskon) as ttlDiskon'), \DB::raw('SUM(total_diskon_tambahan) as ttlDiskonTambahan'))->whereMonth('waktu', $bulan)->whereYear('waktu', $tahun)->get()[0];

        $totalKasMasuk = Kas::select(\DB::raw('SUM(nominal) as ttlKasMasuk'))->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('tipe', '=', 'Masuk')->get()[0];
        
        $totalPemakaian = Pemakaian::select(\DB::raw('SUM(total_saldo) as ttlPemakaian'))->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->get()[0];
        
        $totalKasKeluar = Kas::select(\DB::raw('SUM(nominal) as ttlKasKeluar'))->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('tipe', '=', 'Keluar')->get()[0];

        $this->param['pageInfo'] = 'Laba Rugi';
        $this->param['btnRight']['text'] = '';
        $this->param['btnRight']['link'] = '';
        $this->param['totalPenjualan'] = $totalPenjualan;
        $this->param['totalKasMasuk'] = $totalKasMasuk;
        $this->param['totalPemakaian'] = $totalPemakaian;
        $this->param['totalKasKeluar'] = $totalKasKeluar;

        if ($request->get('print') == 'true') {
            return view('laporan.laba-rugi.print', $this->param);
        }
        else{
            return view('laporan.laba-rugi.laba-rugi', $this->param);
        }
    }
}
