<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Pemakaian;
use \App\DetailPemakaian;
use \App\Barang;

class PemakaianController extends Controller
{
    private $param;
    public function __construct()
    {
        $this->param['icon'] = 'ni-basket text-orange';
    }
    
    public function index()
    {
        $this->param['pageInfo'] = 'List Pemakaian';
        $this->param['btnRight']['text'] = 'Tambah Pemakaian';
        $this->param['btnRight']['link'] = route('pemakaian.create');
        $this->param['pemakaian'] = Pemakaian::paginate(10);
        
        return view('pemakaian-barang.pemakaian.list-pemakaian', $this->param);
    }

    public function create()
    {
        $barang = Barang::get();
        $this->param['pageInfo'] = 'Tambah Pemakaian';
        $this->param['btnRight']['text'] = 'Lihat Pemakaian';
        $this->param['btnRight']['link'] = route('pemakaian.index');
    
        return view('pemakaian-barang.pemakaian.tambah-pemakaian', $this->param, ['barang' => $barang]);
    }

    public function getKode()
    {
        $tgl = explode('-',$_GET['tanggal']);
        $y = $tgl[0];
        $m = $tgl[1];
        $lastKode = \DB::table('pemakaian')
        ->select('kode_pemakaian')
        ->whereMonth('tanggal', $m)
        ->whereYear('tanggal', $y)
        ->orderBy('kode_pemakaian','desc')
        ->skip(0)->take(1)
        ->get();
        if(count($lastKode)==0){
            $dateCreate = date_create($_GET['tanggal']);
            $date = date_format($dateCreate, 'my');
            $kode = "PK".$date."-0001";
        }
        else{
            $ex = explode('-', $lastKode[0]->kode_pemakaian);
            $no = (int)$ex[1] + 1;
            $newNo = sprintf("%04s", $no);
            $kode = $ex[0].'-'.$newNo;
        }

        return $kode;
    }
    
    public function getDetailBarang()
    {
        $kode = $_GET['kode'];
        $detail = \DB::table('barang')
        ->select('satuan', 'stock')
        ->where('kode_barang', $kode)
        ->get();

        // print_r ((array)$detail[0]);
        return json_encode((array)$detail[0]);
    }

    public function addDetailPemakaian()
    {   
        $next = $_GET['biggestNo']+1;
        $barang = Barang::get();
        return view('pemakaian-barang.pemakaian.tambah-detail-pemakaian',['hapus' => true, 'no' => $next, 'barang' => $barang]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tanggal' => 'required',
            'kode_barang.*' => 'required',
            'qty.*' => 'required|numeric',
        ]);

        $ttlQty = 0;
        $totalSaldoPemakaian = 0;
        foreach ($_POST['kode_barang'] as $key => $value) {
            $barang = Barang::select('stock','saldo')->where('kode_barang',$value)->get()[0];
            $hpp = $barang->saldo / $barang->stock;
            $totalSaldoPemakaian += $hpp * $_POST['qty'][$key];
            $ttlQty += $_POST['qty'][$key];
        }

        $newPemakaian = new Pemakaian;
        $newPemakaian->kode_pemakaian = $request->get('kode_pemakaian');
        $newPemakaian->tanggal = $request->get('tanggal');
        $newPemakaian->jumlah_qty = $ttlQty;
        $newPemakaian->total_saldo = $totalSaldoPemakaian;

        $newPemakaian->save();

        foreach ($_POST['kode_barang'] as $key => $value) {
            $barang = Barang::select('stock','saldo')->where('kode_barang', $value)->get()[0];
            $hpp = $barang->saldo / $barang->stock;
            $subtotalSaldo = $hpp * $_POST['qty'][$key];

            $newDetail = new DetailPemakaian;
            $newDetail->kode_pemakaian = $request->get('kode_pemakaian');
            $newDetail->kode_barang = $value;
            $newDetail->qty = $_POST['qty'][$key];
            $newDetail->subtotal_saldo = $subtotalSaldo;
            $newDetail->keterangan = $_POST['keterangan'][$key];
            $newDetail->save();

            $barang = Barang::select('stock','saldo')->where('kode_barang',$value)->get()[0];

            $updateBarang = Barang::findOrFail($value);
            $updateBarang->stock = $barang->stock - $_POST['qty'][$key];
            $updateBarang->saldo = $barang->saldo - $subtotalSaldo;

            $updateBarang->save();
        }

        return redirect()->route('pemakaian.index')->withStatus('Data berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($kode)
    {
        $pemakaian = Pemakaian::findOrFail($kode);

        $detail = DetailPemakaian::where('kode_pemakaian', $kode)->get();

        foreach ($detail as $key => $value) {
            $barang = Barang::findOrFail($value->kode_barang);
            $qty = $value->qty;
            $subtotal_saldo = $value->subtotal_saldo;
            
            $barang->stock = $barang->stock + $qty;
            $barang->saldo = $barang->saldo + $subtotal_saldo;
            
            $barang->save();
            $detailPemakaian = DetailPemakaian::findOrFail($value->id);
            $detailPemakaian->delete();
        }

        $pemakaian->delete();

        return redirect()->route('pemakaian.index')->withStatus('Data berhasil ditambahkan.');
    }
}
