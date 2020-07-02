<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\KategoriMenu;
use \App\Menu;

class MenuController extends Controller
{
    private $param;
    public function __construct()
    {
        $this->param['icon'] = 'ni-box-2 text-primary';
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
        $this->param['btnRight']['link'] = route('menu.create');

        $keyword = $request->get('keyword');

        if($keyword){
            $menus = Menu::with('kategori')->where('nama', 'LIKE', "%$keyword%")->orWhere('kode_menu', 'LIKE', "%$keyword%")->paginate(10);
        }
        else{
            $menus = Menu::with('kategori')->paginate(10);
        }
        return \view('master-menu.menu.index', ['menus' => $menus], $this->param);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->param['pageInfo'] = 'Tambah Menu';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('menu.index');

        $kategoris = KategoriMenu::get();
        return \view('master-menu.menu.create', ['kategoris' => $kategoris], $this->param);
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
            'kode_menu' => 'required|unique:menu|max:10',
            'id_kategori_menu' => 'required|numeric',
            'nama' => 'required|max:30',
            'hpp' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'foto' => 'required|image',
            'status' => 'required',
        ]);

        $foto = $request->file('foto');

        $pathUpload = 'public/assets/img/menu';
        $foto->move($pathUpload,time().".".$foto->getClientOriginalName());
        $namaFoto = time().".".$foto->getClientOriginalName();

        $newMenu = new Menu;

        $newMenu->kode_menu = $request->get('kode_menu');
        $newMenu->id_kategori_menu = $request->get('id_kategori_menu');
        $newMenu->nama = $request->get('nama');
        $newMenu->hpp = $request->get('hpp');
        $newMenu->harga_jual = $request->get('harga_jual');
        $newMenu->foto = $namaFoto;
        $newMenu->status = $request->get('status');

        $newMenu->save();

        return redirect()->route('menu.create')->withStatus('Data berhasil ditambahkan.');
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
        $this->param['pageInfo'] = 'Edit Menu';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('menu.index');

        $kategoris = KategoriMenu::get();
        $menu = Menu::findOrFail($id);

        return view('master-menu.menu.edit', ['menu' => $menu, 'kategoris' => $kategoris], $this->param);
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
        $menu = Menu::findOrFail($kode);
        $isUnique = $menu->kode_menu == $request->get('kode_menu') ? "" : "|unique:menu";

        $validatedData = $request->validate([
            'id_kategori_menu' => 'required|numeric',
            'nama' => 'required|max:30',
            'hpp' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'foto' => 'image',
            'status' => 'required',
        ]);        

        $menu->nama = $request->get('nama');
        $menu->id_kategori_menu = $request->get('id_kategori_menu');
        $menu->hpp = $request->get('hpp');
        $menu->harga_jual = $request->get('harga_jual');
        if($request->file('foto')){
            // $foto = $request->file('foto');
            $pathUpload = 'public/assets/img/menu';
            $foto->move($pathUpload,time().".".$foto->getClientOriginalName());
            $namaFoto = time().".".$foto->getClientOriginalName();
            $menu->foto = $namaFoto;
        }
        $menu->status = $request->get('status');

        $menu->save();

        return redirect()->route('menu.index')->withStatus('Data berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return redirect()->route('menu.index')->withStatus('Data berhasil dihapus.');
    }
}
