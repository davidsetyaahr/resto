<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Charts\PenjualanChart;

class Pages extends Controller
{
    private $param;
    public function __construct()
    {
        $this->param['icon'] = 'ni-tv-2 text-primary';
    }
    public function dashboard()
    {
        if(\Auth::user()->level=='Waiters'){
            return redirect()->route('penjualan.create');
        }   
        else{
            $bulan = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
            $tahun = date('Y');
            $pjPerBulan = [];

            foreach ($bulan as $key => $val) {
                $pj = \App\Penjualan::whereYear('waktu', $tahun)->whereMonth('waktu', $val)->count();
                array_push($pjPerBulan, $pj);
            }

            $penjualanChart = new PenjualanChart;
            $penjualanChart->labels(['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des']);
            $penjualanChart->dataset('Penjualan Per Bulan', 'line', $pjPerBulan)->backgroundcolor('transparent')->color('#ffffff');
            return view('pages.dashboard', [ 'penjualanChart' => $penjualanChart ], $this->param);
        }
    }
    public function form()
    {
        $this->param['pageInfo'] = 'Form input';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = url('pages/list-data');
        return view('pages.form', $this->param);
    }
    public function list()
    {
        $this->param['pageInfo'] = 'List Data';
        $this->param['btnRight']['text'] = 'Tambah Data';
        $this->param['btnRight']['link'] = url('pages/form');
        return view('pages.list-data', $this->param);
    }
}
