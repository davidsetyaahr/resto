<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Penjualan;
use App\DetailPenjualan;
use App\KategoriMenu;
use \App\Menu;
use \App\Meja;
use \App\DetailDiskon;
use Illuminate\Support\Facades\DB;
use Session;
class PenjualanController extends Controller
{
    private $param;
    public function __construct()
    {
        $this->param['icon'] = 'ni-cart text-blue';
    }

    public function index()
    {
        $this->param['pageInfo'] = 'Daftar Penjualan';
        $this->param['btnRight']['text'] = 'Tambah Penjualan';
        $this->param['btnRight']['link'] = route('penjualan.create');
        $this->param['penjualan'] = \DB::table('penjualan as p')->select('p.kode_penjualan','p.nama_customer','p.jenis_order','p.waktu','p.total_harga','p.total_diskon','m.nama_meja','p.total_diskon_tambahan','p.status_bayar')->join('meja as m','p.id_meja','m.id_meja')->where('status_bayar','Belum Bayar')->paginate(10);

        $this->param['meja'] = Meja::orderBy('nama_meja','asc')->get();

        return view('penjualan.penjualan.list-penjualan', $this->param);
    }
    
    public function allPenjualan(Request $request)
    {
        $this->param['pageInfo'] = 'List Penjualan';
        $getAllPenjualan = \DB::table('penjualan as p')->select('p.kode_penjualan','p.nama_customer','p.jenis_order','p.waktu','p.total_harga','p.total_diskon','m.nama_meja','p.total_diskon_tambahan','p.status_bayar','p.deleted_at')->join('meja as m','p.id_meja','m.id_meja')->orderBy('p.waktu', 'desc');

        if ($request->get('date')) {
            $getAllPenjualan->whereBetween('p.waktu', [$request->get('date').' 00:00:00', $request->get('date').' 23:59:59']);
        }

        $this->param['penjualan'] = $getAllPenjualan->paginate(10);

        return view('penjualan.penjualan.all-penjualan', $this->param);
    }

    public function softDelete($kode)
    {
        Penjualan::where('kode_penjualan',$kode)
        ->update([
            'deleted_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->route('penjualan.all')->withStatus('Data berhasil dihapus.');
    }

    public function menuBill()
    {
        $kodeMenu = $_GET['kode_menu'];
        $kodePenjualan = $_GET['kode_penjualan'];

        $data = \DB::table('detail_penjualan as dp')->join('menu as m','dp.kode_menu','m.kode_menu')->select('dp.kode_menu','m.harga_jual','m.nama',\DB::raw('sum(qty) as qty, sum(sub_total) as subtotal'), \DB::raw('dp.diskon/dp.qty as diskon_satuan'))->where('dp.kode_menu',$kodeMenu)->where('kode_penjualan',$kodePenjualan)->get()[0];
        $data = (array)$data;
        $diskon = (int)$data['diskon_satuan']; //$this->getDiskon($kodeMenu);
        $data['diskon_satuan'] = $diskon;
        $data['diskon'] = $data['qty']*$diskon;
        return json_encode($data);
    }
    public function getDetailMenu()
    {
        $kode = $_GET['kode'];
        $detail = \DB::table('menu')
        ->select('harga_jual')
        ->where('kode_menu', $kode)
        ->get();

        return json_encode((array)$detail[0]);
    }
    
    public function getDiskon($paramKode='')
    {
        $current_date = date('Y-m-d H:i:s');
        $kode = isset($_GET['kode']) ? $_GET['kode'] : $paramKode;
        $menu = Menu::select('harga_jual', 'id_kategori_menu')->where('kode_menu', $kode)->get();
        $harga_jual = $menu[0]->harga_jual;
        $id_kategori_menu = $menu[0]->id_kategori_menu;

        $diskon = DetailDiskon::select('diskon.jenis_diskon', 'diskon.diskon', \DB::raw('COUNT(jenis_diskon) AS jml'))
        ->join('diskon', 'diskon.id_diskon', '=', 'detail_diskon.id_diskon')
        ->where('start_date', '<=', "$current_date")
        ->where('end_date', '>=',"$current_date")
        ->where('id_kategori_menu', $id_kategori_menu)
        ->get();
        $potongan = 0;
        if ($diskon[0]->jml > 0) {
            if ($diskon[0]->jenis_diskon == 'Persen') {
                $potongan = $harga_jual * $diskon[0]->diskon / 100;
                return $potongan;
            }
            elseif ($diskon[0]->jenis_diskon == 'Rupiah') {
                $potongan = $diskon[0]->diskon;
                return $potongan;
            }
        }
        else{
            return $potongan;
        }
    }

    public function getKode()
    {
        $current_date = date('Y-m-d');
        $tgl = explode('-', $current_date);
        $y = $tgl[0];
        $m = $tgl[1];
        $lastKode = Penjualan::select('kode_penjualan')
        ->whereMonth('waktu', $m)
        ->whereYear('waktu', $y)
        ->orderBy('kode_penjualan','desc')
        ->skip(0)->take(1)
        ->get();

        if(count($lastKode)==0){
            // $dateCreate = date_create($_GET['waktu']);
            $date = date('my');
            $kode = "INV".$date."-0001";
        }
        else{
            $ex = explode('-', $lastKode[0]->kode_penjualan);
            $no = (int)$ex[1] + 1;
            $newNo = sprintf("%04s", $no);
            $kode = $ex[0].'-'.$newNo;
        }

        return $kode;
    }

    public function addDetailPenjualan()
    {   
        $next = $_GET['biggestNo']+1;
        $menu = Menu::where('status', '=', 'Ready')->select('kode_menu','nama')->get();
        return view('penjualan.penjualan.tambah-detail-penjualan',['hapus' => true, 'no' => $next, 'menu' => $menu]);
    }

    public function create(Request $request)
    {
        $this->param['menu'] = Menu::where('status', '=', 'Ready')->select('kode_menu','nama','foto','harga_jual')->paginate(28);

        if($request->ajax()){
            return view('penjualan.penjualan.loop-menu',['menu' => $this->param['menu']]);
        }

        $this->param['pageInfo'] = 'Daftar Menu';
        $this->param['btnRight']['text'] = 'Lihat Penjualan';
        $this->param['btnRight']['link'] = route('penjualan.index');
        $this->param['kode_penjualan'] = $this->getKode();
        $this->param['kategori'] = KategoriMenu::select('id_kategori_menu','kategori_menu')->get();
        $this->param['meja'] = \DB::table('meja')->where('nama_meja', 'Hotel Room')->orWhereNotIn('id_meja', function($query){
            $query->select('id_meja')->from('penjualan')->where('status_bayar','Belum Bayar');
        })->orderBy('nama_meja','asc')->get();

        
        return view('penjualan.penjualan.tambah-penjualan', $this->param);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode_penjualan' => 'required',
            'nama_customer' => 'required',
            'id_meja' => 'required|numeric',
            'jenis_order' => 'required',
            'kode_menu.*' => 'required',
            'qty.*' => 'required|integer|min:1',
        ]);

        $ttlQty = 0;
        $totalHarga = 0; // harga sebelum diskon dan ppn
        $totalDiskon = 0;
        foreach ($_POST['qty'] as $key => $value) {
            $ttlQty = $ttlQty + $value;
            $totalHarga += $value * $_POST['harga'][$key];
            $totalDiskon += $_POST['diskon'][$key];
        }

        $total = $totalHarga - $totalDiskon; // harga setelah diskon
        $totalPpn = $total * 10 / 100;
        $room_charge = 0;

        $newPenjualan = new Penjualan;
        $newPenjualan->kode_penjualan = $request->get('kode_penjualan');
        $newPenjualan->nama_customer = $request->get('nama_customer');
        $newPenjualan->no_hp = $request->get('no_hp');
        $newPenjualan->id_meja = $request->get('id_meja');
        $newPenjualan->jenis_order = $request->get('jenis_order');
        $newPenjualan->jumlah_item = count($_POST['kode_menu']);
        $newPenjualan->status_bayar = 'Belum Bayar';
        $newPenjualan->jumlah_qty = $ttlQty;
        $newPenjualan->total_harga = $totalHarga; // harga sebelum diskon dan ppn
        $newPenjualan->total_ppn = $totalPpn;
        $newPenjualan->waktu = date('Y-m-d H:i:s');
        $newPenjualan->jumlah_qty = $ttlQty;
        $newPenjualan->jenis_bayar = '';
        $newPenjualan->total_diskon = $totalDiskon;
        $newPenjualan->isTravel = 'False';
        if ($request->get('jenis_order') == 'Room Order') {
            $newPenjualan->nomor_kamar = $request->get('nomor_kamar');
            $room_charge = $total * 10 /100;
        }
        $newPenjualan->room_charge = $room_charge;

        $newPenjualan->save();

        foreach ($_POST['kode_menu'] as $key => $value) {
            $newDetail = new DetailPenjualan;
            $newDetail->kode_penjualan = $request->get('kode_penjualan');
            $newDetail->kode_menu = $value;
            $newDetail->sub_total = $_POST['subtotal'][$key];
            $newDetail->sub_total_ppn = $_POST['subtotal'][$key] * 10 / 100;
            $newDetail->keterangan = $_POST['keterangan'][$key];
            $newDetail->qty = $_POST['qty'][$key];
            $newDetail->diskon = $_POST['diskon'][$key];

            $newDetail->save();
        }
        return redirect()->route('cetak-dapur', ['kode' => $request->get('kode_penjualan')]);
    }

    public function show($id)
    {
        //
    }

    public function edit(Request $request,$kodePenjualan)
    {
        $this->param['menu'] = Menu::where('status', '=', 'Ready')->select('kode_menu','nama','foto','harga_jual')->paginate(28);

        if($request->ajax()){
            return view('penjualan.penjualan.loop-menu',['menu' => $this->param['menu']]);
        }
        $this->param['pageInfo'] = 'Daftar Menu';
        $this->param['btnRight']['text'] = 'Lihat Penjualan';
        $this->param['btnRight']['link'] = route('penjualan.index');
        $this->param['kategori'] = KategoriMenu::select('id_kategori_menu','kategori_menu')->get();
        $this->param['penjualan'] = Penjualan::findOrFail($kodePenjualan);
        $this->param['mejaSelected'] = Meja::where('id_meja',$this->param['penjualan']->id_meja)->get()[0];
        $this->param['meja'] = \DB::table('meja')->whereNotIn('id_meja', function($query){
            $query->select('id_meja')->from('penjualan')
            ->where('status_bayar','Belum Bayar');
        })->orderBy('nama_meja','asc')->get();

        $this->param['detail'] = \DB::table('detail_penjualan as dp')->select('dp.*','dp.sub_total as subtotal','m.harga_jual as harga','m.nama as nama_menu')->join('menu as m','dp.kode_menu','m.kode_menu')->where('kode_penjualan',$kodePenjualan)->get()->toArray();
        $getKodeMenu = DetailPenjualan::select('kode_menu')->where('kode_penjualan',$kodePenjualan)->get()->toArray();
        $arrKode = [];
        foreach ($getKodeMenu as $key => $value) {
            array_push($arrKode, $value['kode_menu']);
        }
        $this->param['menuOnDetail'] = $arrKode;
        return view('penjualan.penjualan.edit-penjualan', $this->param);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $kodePenjualan)
    {
        $validatedData = $request->validate([
            'kode_penjualan' => 'required',
            'nama_customer' => 'required',
            'id_meja' => 'required|numeric',
            'jenis_order' => 'required',
            'kode_menu.*' => 'required',
            'qty.*' => 'required|integer|min:1',
        ]);
        
        $totalHarga = 0;
        $totalDiskon = 0;
        $totalQty = 0;
        $param['dapur'] = [];
        $param['bar'] = [];
        $param['billTambahan'] = [];
        $param['update'] = 'up';
        $param['cetakDapur'] = isset($_POST['print']) ? $_POST['print'] : 'false';
        foreach ($_POST['kode_menu'] as $key => $value) {

            $totalHarga += $_POST['qty'][$key] * $_POST['harga'][$key];
            $totalDiskon += $_POST['diskon'][$key];
            $totalQty += $_POST['qty'][$key];

            if(empty($_POST['id_detail'][$key])){
                //insert new
                $newDetail = new DetailPenjualan;
                $newDetail->kode_penjualan = $request->get('kode_penjualan');
                $newDetail->kode_menu = $_POST['kode_menu'][$key];
                $newDetail->sub_total = $_POST['subtotal'][$key];
                $newDetail->sub_total_ppn = $_POST['subtotal'][$key] * 10 / 100;
                $newDetail->keterangan = $_POST['keterangan'][$key];
                $newDetail->qty = $_POST['qty'][$key];
                $newDetail->diskon = $_POST['diskon'][$key];
    
                $newDetail->save();

                $cekJenis = Menu::select('jenis_menu')->where('kode_menu',$_POST['kode_menu'][$key])->get()[0];
                $arr = array(
                    'qty' => $_POST['qty'][$key],
                    'nama' => $_POST['nama_menu'][$key],
                    'keterangan' => $_POST['keterangan'][$key]
                );
                
                $arrBillTambahan = array(
                    'kode_menu' => $_POST['kode_menu'][$key],
                    'qty' => $_POST['qty'][$key],
                    'nama' => $_POST['nama_menu'][$key],
                    'keterangan' => $_POST['keterangan'][$key],
                    'sub_total' => $_POST['subtotal'][$key],
                    'sub_total_ppn' => $_POST['subtotal'][$key] * 10 / 100,
                    'diskon' => $_POST['diskon'][$key],
                );

                if($cekJenis->jenis_menu=='Dapur'){
                    $param['dapur'][count($param['dapur'])] = $arr;
                }else{
                    $param['bar'][count($param['bar'])] = $arr;
                }

                $param['billTambahan'][count($param['billTambahan'])] = $arrBillTambahan;
            }
            else{
                $getDetail = \DB::table('detail_penjualan as d')->select('d.qty','d.keterangan','m.jenis_menu')->join('menu as m','d.kode_menu','m.kode_menu')->where('d.id_detail_penjualan',$_POST['id_detail'][$key])->get()[0];
                if($getDetail->qty!=$_POST['qty'][$key] || $getDetail->keterangan!=$_POST['keterangan'][$key]){
                    //update   
                    $arr = array(
                        'qty' => ($_POST['qty'][$key] - $getDetail->qty),
                        'nama' => $_POST['nama_menu'][$key],
                        'keterangan' => $_POST['keterangan'][$key]
                    );

                    $arrBillTambahan = array(
                        'kode_menu' => $_POST['kode_menu'][$key],
                        'qty' => ($_POST['qty'][$key] - $getDetail->qty),
                        'nama' => $_POST['nama_menu'][$key],
                        'keterangan' => $_POST['keterangan'][$key],
                        'sub_total' => $_POST['subtotal'][$key] / ($_POST['qty'][$key]),
                        'sub_total_ppn' => $_POST['subtotal'][$key] / ($_POST['qty'][$key]) * 10 / 100,
                        'diskon' => $_POST['diskon'][$key] != 0 ? $_POST['diskon'][$key] / $_POST['diskon'][$key] / ($_POST['qty'][$key]) : 0,
                    );

                    if($getDetail->jenis_menu=='Dapur'){
                        $param['dapur'][count($param['dapur'])] = $arr;
                    }else{
                        $param['bar'][count($param['bar'])] = $arr;
                    }

                    $param['billTambahan'][count($param['billTambahan'])] = $arrBillTambahan;
    
                    DetailPenjualan::where('id_detail_penjualan', $_POST['id_detail'][$key])
                    ->update([
                        'sub_total' => $_POST['subtotal'][$key],
                        'sub_total_ppn' => $_POST['subtotal'][$key] * 10 / 100,
                        'keterangan' => $_POST['keterangan'][$key],
                        'qty' => $_POST['qty'][$key], 
                        'diskon' => $_POST['diskon'][$key],
                    ]);
                }
            }
        }

        if(isset($_POST['id_delete'])){
            foreach ($_POST['id_delete'] as $key => $value) {
                //delete
                DetailPenjualan::where('id_detail_penjualan', $value)->delete();
            }
        }

        $total = $totalHarga - $totalDiskon;
        $totalPpn = $total * 10 / 100;
        $room_charge = 0;
        if ($_POST['jenis_order'] == 'Room Order') {
            $nomor_kamar = $_POST['nomor_kamar'];
            $room_charge = $total * 10 / 100;
        }
        else{
            $nomor_kamar = NULL;
        }
        Penjualan::where('kode_penjualan',$kodePenjualan)
        ->update([
            'nama_customer' => $_POST['nama_customer'],
            'no_hp' => $_POST['no_hp'],
            'id_meja' => $_POST['id_meja'],
            'jenis_order' => $_POST['jenis_order'],
            'jumlah_item' => count($_POST['kode_menu']),
            'total_harga' => $totalHarga,
            'total_ppn' => $totalPpn,
            // 'waktu' => date('Y-m-d H:i:s'),
            'jumlah_qty' => $totalQty,
            'total_diskon' => $totalDiskon,
            'room_charge' => $room_charge,
            'nomor_kamar' => $nomor_kamar,
        ]);

        if(count($param['dapur'])!=0 || count($param['bar'])!=0){
            // if (isset($_POST['print'])) {
            return redirect()->route('cetak-dapur', ['kode' => $kodePenjualan.'?update=up'])->with('data',$param);
            // }
            // else{
            //     return redirect()->route('edit-penjualan', ['kode' => $kodePenjualan]);
            // }
        }
        else{
            return redirect()->route('edit-penjualan', ['kode' => $kodePenjualan]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($kode)
    {
        DetailPenjualan::where('kode_penjualan',$kode)->delete();
        Penjualan::where('kode_penjualan',$kode)->delete();

        return redirect()->route('penjualan.index')->withStatus('Data berhasil dihapus.');


    }

    public function listPembayaran()
    {
        $this->param['pageInfo'] = 'List Pembayaran';
        $this->param['btnRight']['text'] = 'Lihat Penjualan';
        $this->param['btnRight']['link'] = route('penjualan.index');

        $penjualan = Penjualan::where('status_bayar', '=', 'Belum Bayar')->paginate(10);

        return view('penjualan.pembayaran.list-pembayaran', ['penjualan' => $penjualan], $this->param);
    }
    
    public function pembayaran($kode)
    {
        $this->param['pageInfo'] = 'Pembayaran Dengan Kode '.$kode;
        $this->param['btnRight']['text'] = 'Lihat Penjualan';
        $this->param['btnRight']['link'] = route('penjualan.index');

        $penjualan = \DB::table('penjualan as p')->select('p.*','m.nama_meja')->join('meja as m','p.id_meja','m.id_meja')->where('kode_penjualan',$kode)->get()[0];
        $detail = DetailPenjualan::select('detail_penjualan.*','menu.harga_jual')->join('menu','detail_penjualan.kode_menu','menu.kode_menu')->where('kode_penjualan', $kode)->get();

        return view('penjualan.pembayaran.form-pembayaran', ['penjualan' => $penjualan, 'detail' => $detail], $this->param);
    }

    public function savePembayaran(Request $request, $kode)
    {
        if($_POST['tipeBill']=='normal'){
            $penjualan = Penjualan::findOrFail($kode);
            $validatedData = $request->validate([
                'bayar' => 'required|numeric|gte:grand_total',
                'jenis_bayar' => 'required'
            ]);
            
            $penjualan->jenis_bayar = $request->get('jenis_bayar');
            $penjualan->no_kartu = $request->get('no_kartu');
            $penjualan->status_bayar = 'Sudah Bayar';
            $penjualan->total_ppn = $request->get('new_ppn');
            $diskon = $request->get('diskon') > 0 ? $request->get('diskon') * $penjualan->total_harga / 100 : 0;
            $penjualan->total_diskon_tambahan = $request->get('diskon_tambahan') + $diskon;
            $penjualan->bayar = $request->get('bayar');
            $penjualan->kembalian = $request->get('kembalian');
            $penjualan->charge = $request->get('charge');
            $penjualan->waktu_bayar = date('Y-m-d H:i:s');
            if($request->get('isTravel')){
                $penjualan->isTravel = $request->get('isTravel');
            }
            else{
                $penjualan->isTravel = 'False';
            }
    
            $penjualan->save();
    
            return redirect()->route('cetak-bill',$kode.'?payment=pay');    
        }
        else{
            $keys = array_keys($_POST['guestQty']);
            $arrKode = [];
            foreach ($keys as $key => $index) {
                $totalHarga = array_sum($_POST['guestSubtotal'][$index]); //total sbelum ppn dan diskon;
                $diskonMenu = array_sum($_POST['guestDiskon'][$index]); //diskon menu per-guest;
                $sumQty = array_sum($_POST['guestQty'][$index]); //diskon menu per-guest;
                $totalAfterDiskon = $totalHarga - $diskonMenu;
                $ppn = 10 * $totalAfterDiskon / 100;
                $total = $totalAfterDiskon + $ppn;

                $diskonPersen = $_POST['diskon'][$index] * $total / 100;
                
                if($index==1){
                    $kode = $_POST['kode_penjualan'];
                }
                else{
                    $kode = $this->getKode();
                }
                array_push($arrKode, $kode);
                $update = array(
                    'kode_penjualan' => $kode,
                    'nama_customer' => 'Guest '.$index,
                    'jumlah_item' => count($_POST['guestQty'][$index]),
                    'jenis_bayar' => $_POST['jenis_bayar'][$index],
                    'no_kartu' => $_POST['no_kartu'][$index],
                    'status_bayar' => 'Sudah Bayar',
                    'total_harga' => $totalHarga,
                    'total_ppn' => $ppn,
                    'jumlah_qty' => $sumQty,
                    'total_diskon' => $diskonMenu,
                    'total_diskon_tambahan' => $diskonPersen + $_POST['diskon_tambahan'][$index],
                    'bayar' => $_POST['bayar'][$index],
                    'kembalian' => $_POST['kembalian'][$index],
                    'id_meja' => $_POST['id_meja'],
                    'charge' => $_POST['charge'][$index]
                );
                if($index==1){
                    Penjualan::where('kode_penjualan', $kode)->update($update);
                    DetailPenjualan::where('kode_penjualan', $kode)->delete();
                }
                else{
                    Penjualan::insert($update);
                }

                foreach ($_POST['guestMenu'][$index] as $i => $value) {
                    $detail = array(
                        "kode_penjualan" => $kode,
                        "kode_menu" => $value,
                        'status' => 'Belum',
                        'sub_total' => $_POST['guestSubtotal'][$index][$i],
                        'sub_total_ppn' => 10 * $_POST['guestSubtotal'][$index][$i] / 100,
                        'keterangan' => '',
                        'qty' => $_POST['guestQty'][$index][$i],
                        'diskon' => $_POST['guestDiskon'][$index][$i],
                        'diskon_tambahan' => 0
                    );

                    DetailPenjualan::insert($detail);
                }
            }
            $sendKode = implode(",",$arrKode);
            return redirect()->route('cetak-bill',$sendKode.'?payment=pay');
        }
    }
    public function filter()
    {
        $menu = Menu::where('status', '=', 'Ready');
        if($_GET['idKategori']!='' || $_GET['key']!=""){
            if($_GET['key']!='' && $_GET['idKategori']==''){
                $menu->where('nama','like','%'.$_GET['key'].'%');
            }
            else{
                $menu->where('nama','like','%'.$_GET['key'].'%')
                ->where('id_kategori_menu', $_GET['idKategori']);
            }
        }
        $menu->select('kode_menu','nama','foto','harga_jual');
        $filter = $menu->paginate(28)->appends(array('idKategori' => $_GET['idKategori'],'key' => $_GET['key']));

        return view('penjualan.penjualan.loop-menu', ['menu' => $filter]);
    }

    public function laporanGeneral()
    {
        $laporan = Penjualan::where('status_bayar','Sudah Bayar')->whereBetween('waktu_bayar',[$_GET['dari'] . ' 00:00:00',$_GET['sampai'] . ' 23:59:59'])->orderBy('waktu_bayar','asc');
        if ($_GET['tipe_pembayaran']) {
            $laporan->where('jenis_bayar', 'LIKE', "%$_GET[tipe_pembayaran]");
        }
        $laporan->whereNull('deleted_at');
        return $laporan->get();
    }
    
    public function laporanKhusus()
    {
        $laporan = Penjualan::where('status_bayar','Sudah Bayar')->whereBetween('waktu_bayar',[$_GET['dari'] . ' 00:00:00',$_GET['sampai'] . ' 23:59:59'])->orderBy('waktu_bayar','asc');
        if ($_GET['tipe_pembayaran']) {
            $laporan->where('jenis_bayar', 'LIKE', "%$_GET[tipe_pembayaran]");
        }
        return $laporan->get();
    }
    
    public function laporanMenuFavorit()
    {
        $laporan = \DB::table('menu as m')->select(\DB::raw('m.kode_menu,m.nama, SUM(dp.qty) as qty, sum(sub_total) as total'))->leftJoin('detail_penjualan as dp','m.kode_menu','dp.kode_menu')->join('penjualan as p', 'dp.kode_penjualan','p.kode_penjualan')->where('p.status_bayar','Sudah Bayar')->whereBetween('p.waktu_bayar',[$_GET['dari'] . ' 00:00:00',$_GET['sampai'] . ' 23:59:59'])->groupBy('m.kode_menu')->orderBy('qty','desc')->orderBy('total','desc');
        if (auth()->user()->level == 'Kasir') {
            $laporan->whereNull('p.deleted_at');
        }
        return $laporan->get();
    }
    public function laporanTidakTerjual()
    {
        $laporan = \DB::table('menu as m')->select('m.kode_menu','m.nama','m.hpp','m.harga_jual','m.status')->whereNotIn('m.kode_menu', function($query){
            $query->select('dp.kode_menu')->from('detail_penjualan as dp')->join('penjualan as p','dp.kode_penjualan','p.kode_penjualan')->where('p.status_bayar','Sudah Bayar')->whereBetween('p.waktu_bayar',[$_GET['dari'] . ' 00:00:00',$_GET['sampai']. ' 23:59:59']);
        })->orderBy('m.kode_menu','asc')->get();
        return $laporan;
    }
    
    public function laporanPenjualan()
    {
        $this->param['pageInfo'] = 'Laporan Pembayaran';
        $this->param['btnRight']['text'] = 'Tambah Penjualan';
        $this->param['btnRight']['link'] = route('penjualan.create');
        if(isset($_GET['dari']) && isset($_GET['sampai'])){
            if($_GET['tipe']=='general'){
                $this->param['penjualan'] = $this->laporanGeneral();
            }
            else if($_GET['tipe']=='khusus'){
                $this->param['penjualan'] = $this->laporanKhusus();
            }
            else if($_GET['tipe']=='menu-favorit'){
                $this->param['penjualan'] = $this->laporanMenuFavorit();
            }
            else if($_GET['tipe']=='tidak-terjual'){
                $this->param['penjualan'] = $this->laporanTidakTerjual();
            }
        }
        return view('penjualan.laporan.laporan-penjualan', $this->param);
    }
    
    public function printLaporanPenjualan(){
        if($_GET['tipe']=='general'){
            $data['penjualan'] = $this->laporanGeneral();
        }
        else if($_GET['tipe']=='khusus'){
            $data['penjualan'] =  $this->laporanKhusus();
        }
        else if($_GET['tipe']=='menu-favorit'){
            $data['penjualan'] =  $this->laporanMenuFavorit();
        }
        else if($_GET['tipe']=='tidak-terjual'){
            $data['penjualan'] = $this->laporanTidakTerjual();
        }
        return view('penjualan.laporan.print-laporan-penjualan-'.$_GET['tipe'], $data);
    }

    public function menuTerlaris(Request $request)
    {
        $dari = $request->get('dari');
        $sampai = $request->get('sampai');
        $laporan = '';
        if($dari && $sampai){
            $laporan = \DB::table('penjualan')
                            ->whereBetween('waktu', [$dari, $sampai])
                            ->join('detail_penjualan AS dt', \DB::raw('dt.kode_penjualan'), '=', 'penjualan.kode_penjualan')
                            ->join('menu AS m', \DB::raw('dt.kode_menu'), '=', \DB::raw('m.kode_menu'))
                            ->select(\DB::raw('SUM(dt.qty) as jml'), 'dt.kode_menu', 'm.nama')
                            ->groupBy(\DB::raw('dt.kode_menu'))
                            ->orderBy(\DB::raw('jml'), 'DESC')
                            ->get();
        }

        $this->param['pageInfo'] = 'Menu Terlaris';
        $this->param['btnRight']['text'] = '';
        $this->param['btnRight']['link'] = '';
        $this->param['laporan'] = $laporan;

        return view('laporan.laporan-menu.menu-terlaris', ['laporan' => $laporan], $this->param);
    }
    
    public function printMenuTerlaris(Request $request)
    {
        $dari = $request->get('dari');
        $sampai = $request->get('sampai');
        $laporan = '';
        if($dari && $sampai){
            $laporan = \DB::table('penjualan')
                            ->whereBetween('waktu', [$dari, $sampai])
                            ->join('detail_penjualan AS dt', \DB::raw('dt.kode_penjualan'), '=', 'penjualan.kode_penjualan')
                            ->join('menu AS m', \DB::raw('dt.kode_menu'), '=', \DB::raw('m.kode_menu'))
                            ->select(\DB::raw('SUM(dt.qty) as jml'), 'dt.kode_menu', 'm.nama')
                            ->groupBy(\DB::raw('dt.kode_menu'))
                            ->orderBy(\DB::raw('jml'), 'DESC')
                            ->get();
        }

        // $this->param['pageInfo'] = 'Menu Terlaris';
        // $this->param['btnRight']['text'] = '';
        // $this->param['btnRight']['link'] = '';
        $this->param['laporan'] = $laporan;

        return view('laporan.laporan-menu.print-menu-terlaris', ['laporan' => $laporan], $this->param);
    }

    public function menuPalingMenghasilkan(Request $request)
    {
        $dari = $request->get('dari');
        $sampai = $request->get('sampai');
        $laporan = '';
        if($dari && $sampai){
            $laporan = \DB::table('penjualan')
                            ->whereBetween('waktu', [$dari, $sampai])
                            ->join('detail_penjualan AS dt', \DB::raw('dt.kode_penjualan'), '=', 'penjualan.kode_penjualan')
                            ->join('menu AS m', \DB::raw('dt.kode_menu'), '=', \DB::raw('m.kode_menu'))
                            ->select(\DB::raw('SUM(dt.sub_total) - SUM(dt.diskon) as jml'), 'dt.kode_menu', 'm.nama')
                            ->groupBy(\DB::raw('dt.kode_menu'))
                            ->orderBy(\DB::raw('jml'), 'DESC')
                            ->get();
        }

        $this->param['pageInfo'] = 'Menu Paling Menghasilkan';
        $this->param['btnRight']['text'] = '';
        $this->param['btnRight']['link'] = '';
        $this->param['laporan'] = $laporan;

        return view('laporan.laporan-menu.menu-paling-menghasilkan', ['laporan' => $laporan], $this->param);
    }
    
    public function printMenuPalingMenghasilkan(Request $request)
    {
        $dari = $request->get('dari');
        $sampai = $request->get('sampai');
        $laporan = '';
        if($dari && $sampai){
            $laporan = \DB::table('penjualan')
                            ->whereBetween('waktu', [$dari, $sampai])
                            ->join('detail_penjualan AS dt', \DB::raw('dt.kode_penjualan'), '=', 'penjualan.kode_penjualan')
                            ->join('menu AS m', \DB::raw('dt.kode_menu'), '=', \DB::raw('m.kode_menu'))
                            ->select(\DB::raw('SUM(dt.sub_total) - SUM(dt.diskon) as jml'), 'dt.kode_menu', 'm.nama')
                            ->groupBy(\DB::raw('dt.kode_menu'))
                            ->orderBy(\DB::raw('jml'), 'DESC')
                            ->get();
        }

        // $this->param['pageInfo'] = 'Menu Paling Menghasilkan';
        // $this->param['btnRight']['text'] = '';
        // $this->param['btnRight']['link'] = '';
        $this->param['laporan'] = $laporan;

        return view('laporan.laporan-menu.print-menu-paling-menghasilkan', ['laporan' => $laporan], $this->param);
    }

    public function cetakBill($kode,$payment='')
    {
        $resto = \DB::table('perusahaan')->select('nama', 'alamat', 'kota', 'telepon', 'email')->where('id_perusahaan', 1)->get();
        return view('penjualan.cetak.cetak-bill', ['resto' => $resto[0],'payment' => $payment,'kode' => $kode]);
    }

    public function cetakDapur($kode)
    {
        $param['penjualan'] = \DB::table('penjualan as p')->select('p.room_charge','p.nama_customer','p.kode_penjualan', 'p.waktu','m.nama_meja','p.total_diskon','p.total_diskon_tambahan','p.bayar','p.kembalian', 'p.jenis_order', 'p.nomor_kamar', 'p.jenis_bayar', 'p.charge')->join('meja as m','p.id_meja','m.id_meja')->where('p.kode_penjualan',$kode)->get()[0];
        if(!empty(Session::get('data')['update'])){
            $param['bar'] = Session::get('data')['bar'];
            $param['dapur'] = Session::get('data')['dapur'];
            $param['billTambahan'] = Session::get('data')['billTambahan'];
            $param['cetakDapur'] = Session::get('data')['cetakDapur'];
        }
        else{
            $param['bar'] = \DB::table('detail_penjualan as dp')->select('dp.qty','m.nama','dp.keterangan')->join('menu as m','dp.kode_menu','m.kode_menu')->where('dp.kode_penjualan', $kode)->where('m.jenis_menu','Bar')->get();
            $param['dapur'] = \DB::table('detail_penjualan as dp')->select('dp.qty','m.nama','dp.keterangan')->join('menu as m','dp.kode_menu','m.kode_menu')->where('dp.kode_penjualan', $kode)->where('m.jenis_menu','Dapur')->get();        
            $param['cetakDapur'] = 'true';
        }
        $param['resto'] = \DB::table('perusahaan')->select('nama', 'alamat', 'kota', 'telepon', 'email')->where('id_perusahaan', 1)->get();

        return view('penjualan.cetak.cetak-dapur', $param);
    }

    public function changeToPiutang($kode)
    {
        try{
            Penjualan::where('kode_penjualan', $kode)
            ->update([
                'status_bayar' => 'Piutang'
            ]);

            return redirect()->route('penjualan.index')->withStatus('Berhasil menjadikan piutang.');
        }
        catch(Exception $e){
            error_log($e->getMessage());
        }
        catch(\Illuminate\Database\QueryException $e){
            error_log($e->getMessage());
        }
    }

    // public function cetakStruk($kode)
    // {
    //     $penjualan = Penjualan::findOrFail($kode);
    //     $detail = DetailPenjualan::where('kode_pj', $kode)->get();
    //     $toko = \App\SettingToko::findOrFail(1);

    //     return view('penjualan.cetak-struk', ['penjualan' => $penjualan, 'detail' => $detail, 'toko' => $toko]);
    // }
}
