<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PiutangController extends Controller
{
    private $param;
    public function __construct()
    {
        $this->param['icon'] = 'ni-paper-diploma text-success';
    }

    public function index(Request $request)
    {
        $this->param['pageInfo'] = 'List Data';
        // $this->param['btnRight']['text'] = 'Tambah Data';
        // $this->param['btnRight']['link'] = route('kas.create');

        $keyword = $request->get('keyword');
        
        if ($keyword) {
            $this->param['penjualan'] = \DB::table('penjualan as p')->select('p.kode_penjualan','p.nama_customer','p.jenis_order','p.waktu','p.total_harga','p.total_diskon','m.nama_meja','p.total_diskon_tambahan','p.status_bayar')->join('meja as m','p.id_meja','m.id_meja')->where('p.kode_penjualan', 'LIKE', "%$keyword%")->where('status_bayar','Piutang')->paginate(10);
        }
        else{
            $this->param['penjualan'] = \DB::table('penjualan as p')->select('p.kode_penjualan','p.nama_customer','p.jenis_order','p.waktu','p.total_harga','p.total_diskon','m.nama_meja','p.total_diskon_tambahan','p.status_bayar')->join('meja as m','p.id_meja','m.id_meja')->where('status_bayar','Piutang')->paginate(10);
        }
        return view('penjualan.piutang.list-piutang', $this->param);
    }
}
