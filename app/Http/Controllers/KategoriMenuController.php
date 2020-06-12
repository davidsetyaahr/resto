<?php

namespace App\Http\Controllers;

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
            $kategori = KategoriMenu::where('kategori_menu', 'LIKE', "%$keyword%")->paginate(10);
        }
        else{
            $kategori = KategoriMenu::paginate(10);
        }

        $this->param['pageInfo'] = 'List Data';
        $this->param['btnRight']['text'] = 'Tambah Data';
        $this->param['btnRight']['link'] = route('kategori-menu.create');

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

        return view('master-menu.kategori-menu.create', $this->param);
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
            'kategori_menu' => 'required|unique:kategori_menu|max:15',
        ]);

        $newKategoriMenu = new KategoriMenu;

        $newKategoriMenu->kategori_menu = $request->get('kategori_menu');

        $newKategoriMenu->save();

        return redirect()->route('kategori-menu.index')->withStatus('Data berhasil ditambahkan.');
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

        return view('master-menu.kategori-menu.edit', ['kategori_menu' => $kategori], $this->param);
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
    public function destroy($id)
    {
        //
    }
}
