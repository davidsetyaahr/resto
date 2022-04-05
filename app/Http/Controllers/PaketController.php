<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Menu;

class PaketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $param;
    public function __construct()
    {
        $this->param['icon'] = 'ni-archive-02 text-purple';
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->param['pageInfo'] = 'Paket Tanpa PPN';
        $this->param['btnRight']['text'] = 'Lihat Data';
        $this->param['btnRight']['link'] = route('paket.index');
        $this->param['menu'] = Menu::where('status', '=', 'Ready')->select('kode_menu','nama','foto','harga_jual')->get();
        $paket = \DB::table('paket_menu_tanpa_ppn')->select('kode_menu')->get()->toArray();
        $this->param['paket'] = [];
        foreach ($paket as $key => $value) {
            array_push($this->param['paket'],$value->kode_menu);
        }
        return view('paket.tambah-paket', $this->param);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        \DB::table('paket_menu_tanpa_ppn')->delete();
        foreach ($request->input('menu') as $key => $value) {
            \DB::table('paket_menu_tanpa_ppn')->insert(['kode_menu' => $value]);
        }
        return redirect()->route('paket.create')->withStatus('Data berhasil ditambahkan.');
    }
    public function getPaket()
    {
        $paket = \DB::table('paket_menu_tanpa_ppn as pp')->select('m.kode_menu','nama','harga_jual')->join('menu as m','pp.kode_menu','m.kode_menu')->get();
        echo json_encode($paket);
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
    public function destroy($id)
    {
        //
    }
}
