<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pembelian;
use App\DetailPembelian;
use App\Supplier;
use App\Barang;
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
    public function getKode()
    {
        $tgl = explode('-',$_GET['tanggal']);
        $y = $tgl[0];
        $m = $tgl[1];
        $lastKode = Pembelian::select('kode_pembelian')
        ->whereMonth('tanggal', $m)
        ->whereYear('tanggal', $y)
        ->orderBy('kode_pembelian','desc')
        ->skip(0)->take(1)
        ->get();
        if(count($lastKode)==0){
            $dateCreate = date_create($_GET['tanggal']);
            $date = date_format($dateCreate, 'my');
            $kode = "PB".$date."-0001";
        }
        else{
            $ex = explode('-', $lastKode[0]->kode_pembelian);
            $no = (int)$ex[1] + 1;
            $newNo = sprintf("%04s", $no);
            $kode = $ex[0].'-'.$newNo;
        }

        return $kode;
    }
    public function create()
    {
        $this->param['pageInfo'] = 'Tambah Pembelian';
        $this->param['btnRight']['text'] = 'Lihat Pembelian';
        $this->param['btnRight']['link'] = route('pembelian.index');
        $this->param['supplier'] = Supplier::select('kode_supplier','nama_supplier')->get();
        $this->param['barang'] = Barang::select('kode_barang','nama')->get();
        
        return view('pembelian-barang.pembelian.tambah-pembelian', $this->param);
    }
    public function addDetailPembelian()
    {   
        $next = $_GET['biggestNo']+1;
        $barang = Barang::select('kode_barang','nama')->get();
        return view('pembelian-barang.pembelian.tambah-detail-pembelian',['hapus' => true, 'no' => $next, 'barang' => $barang]);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tanggal' => 'required',
            'kode_supplier' => 'required',
            'kode_barang.*' => 'required',
            'qty.*' => 'required',
            'harga.*' => 'required',
        ]);
        
        $ttlQty = 0;
        $total = 0;
        foreach ($_POST['qty'] as $key => $value) {
            $ttlQty = $ttlQty + $value;
            $total = $total + $_POST['subtotal'][$key];
        }
        
        $newPembelian = new Pembelian;
        $newPembelian->kode_pembelian = $request->get('kode_pembelian');
        $newPembelian->tanggal = $request->get('tanggal');
        $newPembelian->jumlah_item = count($_POST['kode_barang']);
        $newPembelian->jumlah_qty = $ttlQty;
        $newPembelian->total = $total;
        $newPembelian->kode_supplier = $request->get('kode_supplier');

        $newPembelian->save();

        foreach ($_POST['kode_barang'] as $key => $value) {
            $newDetail = new DetailPembelian;
            $newDetail->kode_pembelian = $request->get('kode_pembelian');
            $newDetail->kode_barang = $value;
            $newDetail->qty = $_POST['qty'][$key];
            $newDetail->subtotal = $_POST['subtotal'][$key];

            $newDetail->save();

            $barang = Barang::select('stock','saldo')->where('kode_barang',$value)->get()[0];

            $updateBarang = Barang::findOrFail($value);
            $updateBarang->stock = $barang->stock + $_POST['qty'][$key];
            $updateBarang->saldo = $barang->saldo + $_POST['subtotal'][$key];

            $updateBarang->save();
        }

        return redirect()->route('pembelian.index')->withStatus('Data berhasil ditambahkan.');

    }
}
