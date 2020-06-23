<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Diskon;
use \App\DetailDiskon;
use \App\Menu;

class DiskonController extends Controller
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
        $keyword = $request->get('keyword');
        if($keyword){
            $diskon = Diskon::where('nama_diskon', 'LIKE', "%$keyword%")->paginate(10);
        }
        else{
            $diskon = Diskon::paginate(10);
        }
        $this->param['pageInfo'] = 'List Data';
        $this->param['btnRight']['text'] = 'Tambah Data';
        $this->param['btnRight']['link'] = route('diskon.create');

        return view('master-menu.diskon.index', ['diskon' => $diskon], $this->param);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menu = Menu::get();
        $this->param['pageInfo'] = 'Tambah Data';
        $this->param['btnRight']['text'] = 'List Data';
        $this->param['btnRight']['link'] = route('diskon.index');

        return view('master-menu.diskon.create', $this->param, ['menu' => $menu]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function addDetailDiskon()
    {
        $next = $_GET['biggestNo']+1;
        $menu = menu::get();
        return view('master-menu.diskon.tambah-detail-diskon', ['hapus' => true, 'no' => $next, 'menu' => $menu]);
    }

    public function getDetailMenu()
    {
        $kode = $_GET['kode'];
        $detail = \DB::table('menu')
        ->select('harga_jual')
        ->where('kode_menu', $kode)
        ->get();

        // print_r($detail);
        return json_encode((array)$detail[0]);
    }

    public function addEditDetailDiskon()
    {
        $fields = array(
            'kode_menu' => 'kode_menu',
            'harga_jual' => 'harga_jual',
            'harga_setelah_diskon' => 'harga_setelah_diskon',
        );
        $next = $_GET['biggestNo']+1;
        $menu = Menu::select('kode_menu','nama','harga_jual')->get();
        return view('master-menu.diskon.edit-detail-diskon', ['hapus' => true, 'no' => $next, 'menu' => $menu, 'fields' => $fields, 'idDetail' => '0']);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_diskon' => 'required',
            'jenis_diskon' => 'required',
            'diskon' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'kode_menu.*' => 'required',
        ]);

        $newDiskon = new Diskon;

        $newDiskon->nama_diskon = $request->get('nama_diskon');
        $newDiskon->jenis_diskon = $request->get('jenis_diskon');
        $newDiskon->diskon = $request->get('diskon');
        $newDiskon->start_date = $request->get('start_date');
        $newDiskon->end_date = $request->get('end_date');

        $newDiskon->save();
        $getIdDiskon =  Diskon::select(\DB::raw("max(id_diskon) as id"))->get();
        $id_diskon = $getIdDiskon[0]->id;

        foreach($_POST['kode_menu'] as $key => $value){
            $newDetailDiskon = new DetailDiskon;

            $newDetailDiskon->id_diskon = $id_diskon;
            $newDetailDiskon->kode_menu = $value;

            $newDetailDiskon->save();
        }

        return redirect()->route('diskon.index')->withStatus('Data berhasil ditambahkan.');
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
        $this->param['pageInfo'] = 'Edit Diskon';
        $this->param['btnRight']['text'] = 'Lihat Diskon';
        $this->param['btnRight']['link'] = route('diskon.index');
        $this->param['diskon'] = Diskon::findOrFail($id);
        $this->param['detail'] = \DB::table(\DB::raw('detail_diskon as dt'))->select('id_detail_diskon','dt.kode_menu', 'm.harga_jual')->join('menu as m', 'm.kode_menu', '=', 'dt.kode_menu')->where('id_diskon',$id)->get();
        $this->param['menu'] = Menu::select('kode_menu', 'nama', 'harga_jual')->get();

        return view('master-menu.diskon.edit-diskon', $this->param);
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
