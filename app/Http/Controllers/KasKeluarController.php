<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\KasKeluar;

class KasKeluarController extends Controller
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
        $this->param['btnRight']['link'] = route('kas-keluar.create');

        $keyword = $request->get('keyword');
        
        if ($keyword) {
            $kasKeluar = KasKeluar::where('kode_kas', 'LIKE', "%$keyword%")->paginate(10);
        }
        else{
            $kasKeluar = KasKeluar::paginate(10);
        }

        return \view('kas-keluar.kas-keluar.list-kas-keluar', ['kasKeluar' => $kasKeluar], $this->param);
    }

    public function getKode()
    {
        $tgl = explode('-',$_GET['tanggal']);
        $y = $tgl[0];
        $m = $tgl[1];
        $lastKode = \DB::table('kas_keluar')
        ->select('kode_kas')
        ->whereMonth('tanggal', $m)
        ->whereYear('tanggal', $y)
        ->orderBy('kode_kas','desc')
        ->skip(0)->take(1)
        ->get();
        if(count($lastKode)==0){
            $dateCreate = date_create($_GET['tanggal']);
            $date = date_format($dateCreate, 'my');
            $kode = "BBK".$date."-0001";
        }
        else{
            $ex = explode('-', $lastKode[0]->kode_kas);
            $no = (int)$ex[1] + 1;
            $newNo = sprintf("%04s", $no);
            $kode = $ex[0].'-'.$newNo;
        }

        return $kode;
    }

    public function create()
    {
        $this->param['pageInfo'] = 'Tambah Data';
        $this->param['btnRight']['text'] = 'List Data';
        $this->param['btnRight']['link'] = route('kas-keluar.index');

        return \view('kas-keluar.kas-keluar.tambah-kas-keluar', $this->param);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode_kas' => 'required',
            'nominal' => 'required|numeric',
            'tanggal' => 'required|date',
            'penanggung_jawab' => 'required',
        ]);

        $newKas = new KasKeluar;

        $newKas->kode_kas = $request->get('kode_kas');
        $newKas->tanggal = $request->get('tanggal');
        $newKas->nominal = $request->get('nominal');
        $newKas->keterangan = $request->get('keterangan');
        $newKas->penanggung_jawab = $request->get('penanggung_jawab');

        $newKas->save();

        return redirect()->route('kas-keluar.index')->withStatus('Data berhasil ditambahkan.');
    }

    public function show($id)
    {
        //
    }

    public function edit($kode)
    {
        $this->param['pageInfo'] = 'Edit Data';
        $this->param['btnRight']['text'] = 'List Data';
        $this->param['btnRight']['link'] = route('kas-keluar.index');

        $kas = KasKeluar::findOrFail($kode);

        return \view('kas-keluar.kas-keluar.edit-kas-keluar', $this->param, ['kas' => $kas]);
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
            'nominal' => 'required|numeric',
            'tanggal' => 'required|date',
            'penanggung_jawab' => 'required',
        ]);

        $kas = KasKeluar::findOrFail($kode);

        $kas->tanggal = $request->get('tanggal');
        $kas->nominal = $request->get('nominal');
        $kas->keterangan = $request->get('keterangan');
        $kas->penanggung_jawab = $request->get('penanggung_jawab');

        $kas->save();

        return redirect()->route('kas-keluar.index')->withStatus('Data berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($kode)
    {
        $kas = KasKeluar::findOrFail($kode);
        $kas->delete();

        return redirect()->route('kas-keluar.index')->withStatus('Data berhasil dihapus.');
    }

    public function laporan(Request $request)
    {
        $this->param['pageInfo'] = 'Laporan Kas Keluar';
        $this->param['btnRight']['text'] = 'Tambah Kas Keluar';
        $this->param['btnRight']['link'] = route('kas-keluar.create');

        $dari = $request->get('dari');
        $sampai = $request->get('sampai');
        $laporan = '';
        if($dari && $sampai){
            $kas = KasKeluar::orderBy('tanggal','asc');
            $kas->whereBetween('tanggal',[$dari, $sampai]);

            $laporan = $kas->get();
        }

        if($request->get('print')){
            return view('kas-keluar.laporan.print-laporan-kas-keluar', $this->param, ['laporan' => $laporan]);
        }
        else{
            return view('kas-keluar.laporan.laporan-kas-keluar', $this->param, ['laporan' => $laporan]);
        }
    }
}
