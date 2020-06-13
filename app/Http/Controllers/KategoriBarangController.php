<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\KategoriBarang;

class KategoriBarangController extends Controller
{
    private $param;
    public function __construct()
    {
        $this->param['icon'] = 'ni-tag text-primary';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        if ($keyword) {
            $kategori = KategoriBarang::where('kategori_barang', 'LIKE', "%$keyword%")->paginate(10);
        }
        else{
            $kategori = KategoriBarang::paginate(10);
        }

        $this->param['pageInfo'] = 'List Data';
        $this->param['btnRight']['text'] = 'Tambah Data';
        $this->param['btnRight']['link'] = route('kategori-barang.create');

        return view('master-barang.kategori-barang.index', ['kategori_barang' => $kategori],  $this->param);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->param['pageInfo'] = 'Tambah Kategori Barang';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('kategori-barang.index');

        return view('master-barang.kategori-barang.create', $this->param);
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
            'kategori_barang' => 'required|unique:kategori_barang|max:15',
        ]);
        
        $newKategoriBarang = new KategoriBarang;

        $newKategoriBarang->kategori_barang = $request->get('kategori_barang');

        $newKategoriBarang->save();

        return redirect()->route('kategori-barang.index')->withStatus('Data berhasil ditambahkan.');
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
        $this->param['pageInfo'] = 'Edit Kategori Barang';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('kategori-barang.index');

        $kategoriBarang = KategoriBarang::findOrFail($id);

        return view('master-barang.kategori-barang.edit', ['kategori_barang' => $kategoriBarang], $this->param);
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
        $kategoriBarang = KategoriBarang::findOrFail($id);
        $isUnique = $kategoriBarang->kategori_barang == $request->get('kategori_barang') ? "" : "|unique:kategori_barang";

        $validatedData = $request->validate([
            'kategori_barang' => 'required|max:15'.$isUnique,
        ]);
        

        $kategoriBarang->kategori_barang = $request->get('kategori_barang');

        $kategoriBarang->save();

        return redirect()->route('kategori-barang.index')->withStatus('Data berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kategoriBarang = KategoriBarang::findOrFail($id);
        $kategoriBarang->delete();

        return redirect()->route('kategori-barang.index')->withStatus('Data berhasil dihapus.');
    }
}
