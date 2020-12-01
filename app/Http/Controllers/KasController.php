<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Kas;

class KasController extends Controller
{
    private $param;
    public function __construct()
    {
        $this->param['icon'] = 'ni-money-coins text-success';
    }

    public function index(Request $request)
    {
        $this->param['pageInfo'] = 'List Data';
        $this->param['btnRight']['text'] = 'Tambah Data';
        $this->param['btnRight']['link'] = route('kas.create');

        $keyword = $request->get('keyword');
        
        if ($keyword) {
            $kas = Kas::where('kode_kas', 'LIKE', "%$keyword%")->paginate(10);
        }
        else{
            $kas = Kas::paginate(10);
        }

        return \view('kas.kas.list-kas', ['kas' => $kas], $this->param);
    }

    public function getKode()
    {
        $tgl = explode('-',$_GET['tanggal']);
        $y = $tgl[0];
        $m = $tgl[1];
        $tipe = $_GET['tipe'];
        $lastKode = \DB::table('kas')
        ->select('kode_kas')
        ->where(\DB::raw('length(kode_kas)'), 13)
        ->whereMonth('tanggal', $m)
        ->whereYear('tanggal', $y)
        ->where('tipe', $tipe)
        ->orderBy('kode_kas','desc')
        ->skip(0)->take(1)
        ->get();
        $dateCreate = date_create($_GET['tanggal']);
        $date = date_format($dateCreate, 'my');
        if(count($lastKode)==0 && $tipe == 'Masuk'){
            $kode = "BBM-".$date."-0001";
        }
        elseif(count($lastKode)==0 && $tipe == 'Keluar'){
            $kode = "BBK-".$date."-0001";
        }
        else{
            $ex = explode('-', $lastKode[0]->kode_kas);
            $no = (int)$ex[2] + 1;
            $newNo = sprintf("%04s", $no);
            $kode = $ex[0].'-'.$date.'-'.$newNo;
        }
        return $kode;
    }

    public function create()
    {
        $this->param['pageInfo'] = 'Tambah Data';
        $this->param['btnRight']['text'] = 'List Data';
        $this->param['btnRight']['link'] = route('kas.index');

        return \view('kas.kas.tambah-kas', $this->param);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode_kas' => 'required',
            'tipe' => 'required',
            'nominal' => 'required|numeric',
            'tanggal' => 'required|date',
            'jenis' => 'required',
        ]);

        $newKas = new Kas;

        $newKas->kode_kas = $request->get('kode_kas');
        $newKas->tipe = $request->get('tipe');
        $newKas->tanggal = $request->get('tanggal');
        $newKas->nominal = $request->get('nominal');
        $newKas->keterangan = $request->get('keterangan');
        $newKas->jenis = $request->get('jenis');

        $newKas->save();

        return redirect()->route('kas.index')->withStatus('Data berhasil ditambahkan.');
    }

    public function show($id)
    {
        //
    }

    public function edit($kode)
    {
        $this->param['pageInfo'] = 'Edit Data';
        $this->param['btnRight']['text'] = 'List Data';
        $this->param['btnRight']['link'] = route('kas.index');

        $kas = Kas::findOrFail($kode);

        return \view('kas.kas.edit-kas', $this->param, ['kas' => $kas]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $kode)
    {
        $validatedData = $request->validate([
            'kode_kas' => 'required',
            'tipe' => 'required',
            'nominal' => 'required|numeric',
            // 'tanggal' => 'required|date',
            'jenis' => 'required',
        ]);

        $kas = Kas::findOrFail($kode);

        // $kas->tanggal = $request->get('tanggal');
        $kas->nominal = $request->get('nominal');
        $kas->keterangan = $request->get('keterangan');
        $kas->jenis = $request->get('jenis');
        $kas->tipe = $request->get('tipe');

        $kas->save();

        return redirect()->route('kas.index')->withStatus('Data berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($kode)
    {
        $kas = Kas::findOrFail($kode);
        $kas->delete();

        return redirect()->route('kas.index')->withStatus('Data berhasil dihapus.');
    }

    public function laporan(Request $request)
    {
        $this->param['pageInfo'] = 'Laporan Kas ';
        $this->param['btnRight']['text'] = 'Tambah Kas ';
        $this->param['btnRight']['link'] = route('kas.create');

        $dari = $request->get('dari');
        $sampai = $request->get('sampai');
        $jenis = $request->get('jenis');
        $laporan = '';
        $kas = Kas::orderBy('tanggal','asc');
        if($dari && $sampai){
            $kas->whereBetween('tanggal',[$dari, $sampai]);            
        }

        if ($jenis != 'Semua') {
            $kas->where('jenis', $jenis);
        }

        $laporan = $kas->get();

        if($request->get('print')){
            return view('kas.laporan.print-laporan-kas', $this->param, ['laporan' => $laporan]);
        }
        else{
            return view('kas.laporan.laporan-kas', $this->param, ['laporan' => $laporan]);
        }
    }
}
