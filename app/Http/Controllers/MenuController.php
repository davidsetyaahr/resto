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
        $this->param['icon'] = 'ni-bullet-list-67 text-dark';
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
        $keywordKategori = $request->get('kategori-menu');
        
        $kategori = KategoriMenu::get();
        $menus = Menu::with('kategori');

        if($keywordKategori){
            $menus->where('id_kategori_menu', $keywordKategori);
        }

        if ($keyword) {
            $menus->where('nama', 'LIKE', "%$keyword%");
        }
        return \view('master-menu.menu.index', ['menus' => $menus->paginate(10), 'kategori' => $kategori], $this->param);
    }

    public function getKode()
    {
        $lastKode = Menu::select('kode_menu')
        ->orderBy('kode_menu','desc')
        ->skip(0)->take(1)
        ->get();

        if(count($lastKode)==0){
            $kode = "MN-0001";
        }
        else{
            $ex = explode('-', $lastKode[0]->kode_menu);
            $no = (int)$ex[1] + 1;
            $newNo = sprintf("%04s", $no);
            $kode = $ex[0].'-'.$newNo;
        }

        return $kode;
    }

    public function create()
    {
        $this->param['pageInfo'] = 'Tambah Menu';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('menu.index');
        $this->param['kode_menu'] = $this->getKode();

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
            'status' => 'required',
            'jenis_menu' => 'required',
        ]);
        if ($request->file('foto')) {
            $foto = $request->file('foto');
            $pathUpload = 'public/assets/img/menu';
            $foto->move($pathUpload,time().".".$foto->getClientOriginalName());
            $namaFoto = time().".".$foto->getClientOriginalName();
        }
        else{
            $namaFoto = 'default-menu.png';
        }


        $newMenu = new Menu;

        $newMenu->kode_menu = $request->get('kode_menu');
        $newMenu->id_kategori_menu = $request->get('id_kategori_menu');
        $newMenu->nama = $request->get('nama');
        $newMenu->hpp = $request->get('hpp');
        $newMenu->harga_jual = $request->get('harga_jual');
        $newMenu->foto = $namaFoto;
        $newMenu->status = $request->get('status');
        $newMenu->jenis_menu = $request->get('jenis_menu');

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
            'jenis_menu' => 'required',
        ]);        

        $menu->nama = $request->get('nama');
        $menu->id_kategori_menu = $request->get('id_kategori_menu');
        $menu->hpp = $request->get('hpp');
        $menu->harga_jual = $request->get('harga_jual');
        if($request->file('foto')){
            $foto = $request->file('foto');
            $pathUpload = 'public/assets/img/menu';
            $foto->move($pathUpload,time().".".$foto->getClientOriginalName());
            $namaFoto = time().".".$foto->getClientOriginalName();
            $menu->foto = $namaFoto;
        }
        $menu->status = $request->get('status');
        $menu->jenis_menu = $request->get('jenis_menu');

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
