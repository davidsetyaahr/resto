<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Diskon;
use \App\DetailDiskon;
use \App\KategoriMenu;

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
        $kategori_menu = KategoriMenu::get();
        $this->param['pageInfo'] = 'Tambah Data';
        $this->param['btnRight']['text'] = 'List Data';
        $this->param['btnRight']['link'] = route('diskon.index');

        return view('master-menu.diskon.create', $this->param, ['kategori_menu' => $kategori_menu]);
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
        $kategori_menu = KategoriMenu::get();
        return view('master-menu.diskon.tambah-detail-diskon', ['hapus' => true, 'no' => $next, 'kategori_menu' => $kategori_menu]);
    }

    // public function getDetailMenu()
    // {
    //     $kode = $_GET['kode'];
    //     $detail = \DB::table('menu')
    //     ->select('harga_jual')
    //     ->where('kode_menu', $kode)
    //     ->get();

    //     // print_r($detail);
    //     return json_encode((array)$detail[0]);
    // }

    public function addEditDetailDiskon()
    {
        $fields = array(
            'id_kategori_menu' => 'id_kategori_menu',
            // 'harga_jual' => 'harga_jual',
            // 'harga_setelah_diskon' => 'harga_setelah_diskon',
        );
        $next = $_GET['biggestNo']+1;
        $kategori_menu = KategoriMenu::select('id_kategori_menu','kategori_menu')->get();
        return view('master-menu.diskon.edit-detail-diskon', ['hapus' => true, 'no' => $next, 'kategori_menu' => $kategori_menu, 'fields' => $fields, 'idDetail' => '0']);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_diskon' => 'required',
            'jenis_diskon' => 'required',
            'diskon' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'id_kategori_menu.*' => 'required',
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

        foreach($_POST['id_kategori_menu'] as $key => $value){
            $newDetailDiskon = new DetailDiskon;

            $newDetailDiskon->id_diskon = $id_diskon;
            $newDetailDiskon->id_kategori_menu = $value;

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
        $this->param['detail'] = \DB::table('detail_diskon')->select('id_detail_diskon','id_kategori_menu')->where('id_diskon',$id)->get();
        $this->param['kategori_menu'] = KategoriMenu::select('id_kategori_menu', 'kategori_menu')->get();

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
        $validatedData = $request->validate([
            'nama_diskon' => 'required',
            'jenis_diskon' => 'required',
            'diskon' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'id_kategori_menu.*' => 'required',
        ]);

        foreach ($_POST['id_kategori_menu'] as $key => $value) {
            if($_POST['id_detail_diskon'][$key]!=0){
                $getDetail = DetailDiskon::select('id_kategori_menu')->where('id_detail_diskon',$_POST['id_detail_diskon'][$key])->get()[0];

                if($_POST['id_kategori_menu'][$key] != $getDetail['id_kategori_menu']){
                    DetailDiskon::where('id_detail_diskon',$_POST['id_detail_diskon'][$key])
                    ->update([
                        'id_kategori_menu' => $_POST['id_kategori_menu'][$key],
                    ]);
                }
            }
            else{
                $newDetail = new DetailDiskon;
                $newDetail->id_diskon = $id;
                $newDetail->id_kategori_menu = $_POST['id_kategori_menu'][$key];
                $newDetail->save();
            }
        }
        if(isset($_POST['id_delete'])){
            foreach ($_POST['id_delete'] as $key => $value) {
                DetailDiskon::where('id_detail_diskon', $value)->delete();
            }
        }
        
        Diskon::where('id_diskon', $id)
        ->update([
            'nama_diskon' => $_POST['nama_diskon'],
            'jenis_diskon' => $_POST['jenis_diskon'],
            'diskon' => $_POST['diskon'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
        ]);

        return redirect()->route('diskon.index')->withStatus('Data berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DetailDiskon::where('id_diskon', $id)->delete();
        Diskon::where('id_diskon', $id)->delete();

        return redirect()->route('diskon.index')->withStatus('Data berhasil dihapus.');
    }
}
