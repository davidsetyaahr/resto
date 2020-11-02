<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Charts\PenjualanChart;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $param;
    public function __construct()
    {
        $this->middleware('auth');
        $this->param['icon'] = 'ni-tv-2 text-primary';
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(\Auth::user()->level=='Waiters'){
            return redirect()->route('penjualan.create');
        }   
        else{
            $bulan = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
            $tahun = date('Y');
            $pjPerBulan = [];

            foreach ($bulan as $key => $val) {
                $pj = \App\Penjualan::where('status_bayar','Sudah Bayar')->whereYear('waktu', $tahun)->whereMonth('waktu', $val)->count();
                array_push($pjPerBulan, $pj);
            }

            $penjualanChart = new PenjualanChart;
            $penjualanChart->labels(['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des']);
            $penjualanChart->dataset('Penjualan Per Bulan', 'line', $pjPerBulan)->backgroundcolor('transparent')->color('#ffffff');
            return view('pages.dashboard', [ 'penjualanChart' => $penjualanChart ], $this->param);
        }
    }
}
