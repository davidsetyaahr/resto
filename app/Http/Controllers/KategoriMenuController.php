<?php

namespace App\Http\Controllers;

use App\GrupKategori;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\KategoriMenu;

class KategoriMenuController extends Controller
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
        if($keyword){
            $kategori = KategoriMenu::where('kategori_menu', 'LIKE', "%$keyword%")->with('grup_kategori')->paginate(10);
        }
        else{
            $kategori = KategoriMenu::with('grup_kategori')->paginate(10);
        }

        $this->param['pageInfo'] = 'List Data';
        $this->param['btnRight']['text'] = 'Tambah Data';
        $this->param['btnRight']['link'] = route('kategori-menu.create');

//        dd($kategori->toArray());

        return view('master-menu.kategori-menu.index', ['kategori_menu' => $kategori], $this->param);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->param['pageInfo'] = 'Tambah Kategori Menu';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('kategori-menu.index');

        return view('master-menu.kategori-menu.create', ['grup_kategori' => GrupKategori::get()], $this->param);
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
            'id_grup_kategori' => 'required|exists:grup_kategori,id',
            'kategori_menu' => 'required|unique:kategori_menu|max:15',
        ]);

        $newKategoriMenu = new KategoriMenu;

        $newKategoriMenu->id_grup_kategori = $request->id_grup_kategori;
        $newKategoriMenu->kategori_menu = $request->get('kategori_menu');

        $newKategoriMenu->save();

        return redirect()->route('kategori-menu.create')->withStatus('Data berhasil ditambahkan.');
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
        $kategori = KategoriMenu::findOrFail($id);
        $this->param['pageInfo'] = 'Edit Kategori Menu';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('kategori-menu.index');

        return view('master-menu.kategori-menu.edit', ['kategori_menu' => $kategori, 'grup_kategori' => GrupKategori::get()], $this->param);
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
        $KategoriMenu = KategoriMenu::findOrFail($id);
        $isUnique = $KategoriMenu->kategori_menu == $request->get('kategori_menu') ? "" : "|unique:kategori_menu";

        $validatedData = $request->validate([
            'id_grup_kategori' => 'required|exists:grup_kategori,id',
            'kategori_menu' => 'required|max:15'.$isUnique,
        ]);

        $KategoriMenu->id_grup_kategori = $request->get('id_grup_kategori');
        $KategoriMenu->kategori_menu = $request->get('kategori_menu');

        $KategoriMenu->save();

        return redirect()->route('kategori-menu.index')->withStatus('Data berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $KategoriMenu = KategoriMenu::findOrFail($id);
        $KategoriMenu->delete();

        return redirect()->route('kategori-menu.index')->withStatus('Data berhasil dihapus.');
    }
}
