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
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tanggal' => 'required',
            'kode_barang.*' => 'required',
            'qty.*' => 'required|numeric|lte:sisa_stock.*',
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

    public function addEditDetailPemakaian()
    {   
        $fields = array(
            'kode_barang' => 'kode_barang',
            'sisa_stock' => 'sisa_stock',
            'qty' => 'qty',
            'satuan' => 'satuan',
            'keterangan' => 'keterangan',
        );
        $next = $_GET['biggestNo']+1;
        $barang = Barang::select('kode_barang','nama')->get();
        return view('pemakaian-barang.pemakaian.edit-detail-pemakaian',['hapus' => true, 'no' => $next, 'barang' => $barang, 'fields' => $fields,'idDetail' => '0']);
    }

    public function edit($kode)
    {
        $this->param['pageInfo'] = 'Edit Pemakaian';
        $this->param['btnRight']['text'] = 'Lihat Pemakaian';
        $this->param['btnRight']['link'] = route('pemakaian.index');
        $this->param['pemakaian'] = Pemakaian::findOrFail($kode);
        // $this->param['detail'] = DetailPemakaian::select('id','kode_barang','qty','subtotal_saldo', 'keterangan')->where('kode_pemakaian',$kode)->get();
        $this->param['detail'] = \DB::table('detail_pemakaian AS dt')
                                        ->select('dt.id','dt.kode_barang', 'dt.qty', 'dt.subtotal_saldo', 'dt.keterangan', 'b.satuan', 'b.stock as sisa_stock')
                                        ->join('barang AS b', 'b.kode_barang', '=', 'dt.kode_barang')
                                        ->where('kode_pemakaian', $kode)
                                        ->get();
        $this->param['barang'] = Barang::select('kode_barang','nama')->get();

        
        return view('pemakaian-barang.pemakaian.edit-pemakaian', $this->param);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'tanggal' => 'required',
            'kode_barang.*' => 'required',
            'qty.*' => 'required|numeric|lte:sisa_stock.*',
        ]);

        $newQty = 0;
        $newTotal =0;
        foreach ($_POST['kode_barang'] as $key => $value) {
            $getHppBarang = Barang::select('stock', 'saldo')->where('kode_barang', $_POST['kode_barang'][$key])->get()[0];
            $hpp = $getHppBarang->saldo / $getHppBarang->stock;
            //update barang
            $subtotal = $hpp * $_POST['qty'][$key];
            if($_POST['id_detail'][$key]!=0){
                $getDetail = DetailPemakaian::select('kode_barang','qty','subtotal_saldo')->where('id',$_POST['id_detail'][$key])->get()[0];

                if($_POST['kode_barang'][$key]!=$getDetail['kode_barang'] || $_POST['qty'][$key]!=$getDetail['qty']){

                    //kembalikan stock & saldo barang
                    Barang::where('kode_barang',$_POST['kode_barang'][$key])
                    ->update([
                        'stock' => \DB::raw('stock+'.$getDetail->qty),
                        'saldo' => \DB::raw('saldo+'.$getDetail->subtotal_saldo),
                        ]);
                        
                        //perbarui stock
                        Barang::where('kode_barang',$_POST['kode_barang'][$key])
                        ->update([
                            'stock' => \DB::raw('stock-'.$_POST['qty'][$key]),
                            'saldo' => \DB::raw('saldo-'.$subtotal),
                            ]);
                            
                            //update detail
                            DetailPemakaian::where('id',$_POST['id_detail'][$key])
                            ->update([
                                'qty' => $_POST['qty'][$key],
                                'subtotal_saldo' => $subtotal,
                    ]);
                    
                }
            }
            else{
                Barang::where('kode_barang',$_POST['kode_barang'][$key])
                ->update([
                    'stock' => \DB::raw('stock-'.$_POST['qty'][$key]),
                    'saldo' => \DB::raw('saldo-'.$subtotal),
                    ]);
                    
                    //insert to detail
                    DetailPemakaian::where('kode_pemakaian',$_POST['kode_pemakaian'])
                    ->insert([
                        'kode_pemakaian' => $_POST['kode_pemakaian'],
                        'kode_barang' => $_POST['kode_barang'][$key],
                        'qty' => $_POST['qty'][$key],
                        'subtotal_saldo' => $subtotal,
                        'keterangan' => $_POST['keterangan'][$key],
                        ]);
            }
            $newQty = $newQty + $_POST['qty'][$key];
            $newTotal = $newTotal + $subtotal;
        }
        if(isset($_POST['id_delete'])){
            foreach ($_POST['id_delete'] as $key => $value) {
                $getDetail = DetailPemakaian::select('kode_barang','qty','subtotal_saldo')->where('id',$value)->get()[0];
                //update barang
                Barang::where('kode_barang',$getDetail->kode_barang)
                ->update([
                    'stock' => \DB::raw('stock+'.$getDetail->qty),
                    'saldo' => \DB::raw('saldo+'.$getDetail->subtotal_saldo),
                    ]);
                    //delete detail
                    DetailPemakaian::where('id',$value)->delete();
                }
        }
        
        //update pemakaian
        Pemakaian::where('kode_pemakaian',$_POST['kode_pemakaian'])
        ->update([
            'tanggal' => $_POST['tanggal'],
            'jumlah_qty' => $newQty,
            'total_saldo' => $newTotal,
            ]);

            return redirect()->route('pemakaian.index')->withStatus('Data berhasil diperbarui.');
    }

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

    public function laporan(Request $request)
    {
        $this->param['pageInfo'] = 'Laporan Pemakaian';
        $this->param['btnRight']['text'] = 'Tambah Pemakaian';
        $this->param['btnRight']['link'] = route('pemakaian.create');

        $dari = $request->get('dari');
        $sampai = $request->get('sampai');
        $laporan = '';
        if($dari && $sampai){
            $pemakaian = Pemakaian::orderBy('tanggal','asc');
            $pemakaian->whereBetween('tanggal',[$dari, $sampai]);

            $laporan = $pemakaian->get();
        }

        if($request->get('print')){
            return view('pemakaian-barang.laporan-pemakaian.print-laporan-pemakaian', $this->param, ['laporan' => $laporan]);
        }
        else{
            return view('pemakaian-barang.laporan-pemakaian.laporan-pemakaian', $this->param, ['laporan' => $laporan]);
        }
    }

    public function barangSeringTerpakai(Request $request)
    {
        $this->param['pageInfo'] = 'Barang Sering Dipakai';
        $this->param['btnRight']['text'] = 'Tambah Pemakaian';
        $this->param['btnRight']['link'] = route('pemakaian.create');

        $dari = $request->get('dari');
        $sampai = $request->get('sampai');
        $laporan = '';
        if($dari && $sampai){
            $laporan = \DB::table('pemakaian')
                            ->whereBetween('tanggal', [$dari, $sampai])
                            ->join('detail_pemakaian AS dt', \DB::raw('dt.kode_pemakaian'), '=', 'pemakaian.kode_pemakaian')
                            ->join('barang AS b', \DB::raw('dt.kode_barang'), '=', \DB::raw('b.kode_barang'))
                            ->select(\DB::raw('SUM(dt.qty) as jml'), 'dt.kode_barang', 'b.nama', 'b.satuan')
                            ->groupBy(\DB::raw('dt.kode_barang'))
                            ->orderBy(\DB::raw('jml'), 'DESC')
                            ->get();
        }

        return view('pemakaian-barang.barang-sering-dipakai.barang-sering-dipakai', ['laporan' => $laporan], $this->param);
    }
    
    public function barangDeathStock()
    {
        $this->param['pageInfo'] = 'Barang Death Stock';
        $this->param['btnRight']['text'] = 'Tambah Pemakaian';
        $this->param['btnRight']['link'] = route('pemakaian.create');

        $bulanSekarang = date('m');
        $bulanAwal = $bulanSekarang - 2;

        $dari = date('Y').'-'.$bulanAwal.'-01';
        $sampai = date('Y').'-'.$bulanSekarang.'-'.date('t');
        
        $laporan = \DB::table('detail_pemakaian as dt')
                        ->whereBetween('p.tanggal', [$dari, $sampai])
                        ->join('pemakaian AS p', \DB::raw('dt.kode_pemakaian'), '=', 'p.kode_pemakaian')
                        ->select('dt.kode_barang')
                        ->distinct()
                        ->get();
        $in = [];
        foreach ($laporan as $key => $value) {
            array_push($in, $value->kode_barang);
        }
        
        $deathStock = Barang::whereNotIn('kode_barang', $in)->get();
        
        return view('pemakaian-barang.barang-death-stock.barang-death-stock', ['deathStock' => $deathStock], $this->param);
    }
}
