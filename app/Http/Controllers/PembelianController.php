<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pembelian;
use App\Supplier;
class PembelianController extends Controller
{
    private $param;
    public function __construct()
    {
        $this->param['icon'] = 'ni-bag-17 text-yellow';
    }

    public function index()
    {
        $this->param['pageInfo'] = 'Daftar Pembelian';
        $this->param['btnRight']['text'] = 'Tambah Pembelian';
        $this->param['btnRight']['link'] = route('pembelian.create');
        $this->param['pembelian'] = Pembelian::paginate(10);
        
        return view('pembelian-barang.pembelian.list-pembelian', $this->param);
    }
    public function create()
    {
        $this->param['pageInfo'] = 'Tambah Pembelian';
        $this->param['btnRight']['text'] = 'Lihat Pembelian';
        $this->param['btnRight']['link'] = route('pembelian.index');
        $this->param['supplier'] = Supplier::select('kode_supplier','nama_supplier')->get();
    
        return view('pembelian-barang.pembelian.tambah-pembelian', $this->param);
    }
    public function addDetailPembelian()
    {   
        $next = $_GET['biggestNo']+1;
        return view('pembelian-barang.pembelian.tambah-detail-pembelian',['hapus' => true, 'no' => $next]);
    }
    public function store(Request $request)
    {
        # code...
    }
}
