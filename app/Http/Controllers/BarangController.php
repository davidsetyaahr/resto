<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\KategoriBarang;
use \App\Barang;

class BarangController extends Controller
{
    private $param;
    public function __construct()
    {
        $this->param['icon'] = 'ni-box-2 text-info';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->param['pageInfo'] = 'List Data';
        $this->param['btnRight']['text'] = 'Tambah Data';
        $this->param['btnRight']['link'] = route('barang.create');

        $keyword = $request->get('keyword');
        
        if ($keyword) {
            $barangs = Barang::with('kategori')->where('nama', 'LIKE', "%$keyword%")->orWhere('kode_barang', 'LIKE', "%$keyword%")->paginate(10);
        }
        else{
            $barangs = Barang::with('kategori')->paginate(10);
        }

        return \view('master-barang.barang.index', ['barangs' => $barangs], $this->param);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->param['pageInfo'] = 'Tambah Data';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('barang.index');

        $kategoris = KategoriBarang::get();
        return \view('master-barang.barang.create', ['kategoris' => $kategoris], $this->param);

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
            'kode_barang' => 'required|unique:barang|max:10',
            'id_kategori_barang' => 'required|numeric',
            'nama' => 'required|max:25',
            'satuan' => 'required|max:10',
            'stock' => 'numeric',
            'saldo' => 'numeric',
            'minimum_stock' => 'numeric',
            // 'exp_date' => 'date',
        ]);

        $newBarang = new Barang;

        $newBarang->kode_barang = $request->get('kode_barang');
        $newBarang->id_kategori_barang = $request->get('id_kategori_barang');
        $newBarang->nama = $request->get('nama');
        $newBarang->satuan = $request->get('satuan');
        $newBarang->stock = $request->get('stock');
        $newBarang->saldo = $request->get('saldo');
        $newBarang->stock_awal = $request->get('stock_awal');
        $newBarang->saldo_awal = $request->get('saldo_awal');
        $newBarang->minimum_stock = $request->get('minimum_stock');
        $newBarang->exp_date = $request->get('exp_date');
        $newBarang->keterangan = $request->get('keterangan');
        $newBarang->tempat_penyimpanan = $request->get('tempat_penyimpanan');

        $newBarang->save();

        return redirect()->route('barang.store')->withStatus('Data berhasil ditambahkan.');
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
    public function edit($kode)
    {
        $this->param['pageInfo'] = 'Edit Data';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('barang.index');

        $kategoris = KategoriBarang::get();
        $barang = Barang::with('kategori')->findOrFail($kode);
        return \view('master-barang.barang.edit', ['kategoris' => $kategoris, 'barang' => $barang], $this->param);
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
        $barang = Barang::findOrFail($kode);
        $isUnique = $barang->kode_barang == $request->get('kode_barang') ? "" : "|unique:barang";

        $validatedData = $request->validate([
            'kode_barang' => 'required|max:10'.$isUnique,
            'id_kategori_barang' => 'required|numeric',
            'nama' => 'required|max:25',
            'satuan' => 'required|max:10',
            'stock' => 'numeric',
            'saldo' => 'numeric',
            'minimum_stock' => 'numeric',
            // 'exp_date' => 'date',
        ]);

        $barang->kode_barang = $request->get('kode_barang');
        $barang->id_kategori_barang = $request->get('id_kategori_barang');
        $barang->nama = $request->get('nama');
        $barang->satuan = $request->get('satuan');
        $barang->stock += $request->get('stock');
        $barang->saldo = $request->get('saldo');
        $barang->stock_awal = $request->get('stock');
        $barang->saldo_awal = $request->get('saldo');
        $barang->minimum_stock = $request->get('minimum_stock');
        $barang->exp_date = $request->get('exp_date');
        $barang->keterangan = $request->get('keterangan');
        $barang->tempat_penyimpanan = $request->get('tempat_penyimpanan');

        $barang->save();

        return redirect()->route('barang.index')->withStatus('Data berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index')->withStatus('Data berhasil dihapus.');
    }

    public function barangMinimum()
    {
        $this->param['pageInfo'] = 'Barang Minimum';
        $this->param['btnRight']['text'] = '';
        $this->param['btnRight']['link'] = '';

        $barangMinimum = Barang::whereRaw('stock <= minimum_stock')->get();
        return view('master-barang.barang-minimum.barang-minimum', ['barangMinimum' => $barangMinimum],$this->param);
    }

    public function posisiStock(Request $request)
    {
        $this->param['pageInfo'] = 'Posisi Stock';
        $this->param['btnRight']['text'] = '';
        $this->param['btnRight']['link'] = '';

        $dari = $request->get('dari');
        $sampai = $request->get('sampai');
        $kode = $request->get('kode_barang');
        $allBarang = Barang::select('kode_barang', 'nama')->get();
        $barang = '';

        if ($dari && $sampai && !$kode) {
            $barang = Barang::where('stock', '>', 0)->get();
        }
        elseif ($kode && $dari && $sampai) {
            $barang = Barang::where('kode_barang', $kode)->get();
        }

        if ($request->get('print') == 'true') {
            return view('master-barang.posisi-stock.print', $this->param, ['barang' => $barang, 'allBarang' => $allBarang]);
        }
        else{
            return view('master-barang.posisi-stock.posisi-stock', $this->param, ['barang' => $barang, 'allBarang' => $allBarang]);
        }
    }
}
