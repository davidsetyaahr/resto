<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Penjualan;
use App\DetailPenjualan;
use App\KategoriMenu;
use \App\Menu;
use \App\DetailDiskon;

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
        $this->param['penjualan'] = Penjualan::paginate(10);
        
        return view('penjualan.penjualan.list-penjualan', $this->param);
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
    
    public function getDiskon()
    {
        $current_date = date('Y-m-d H:i:s');
        $kode = $_GET['kode'];
        $menu = Menu::select('harga_jual')->where('kode_menu', $kode)->get();
        $harga_jual = $menu[0]->harga_jual;

        $diskon = DetailDiskon::select('diskon.jenis_diskon', 'diskon.diskon', \DB::raw('COUNT(jenis_diskon) AS jml'))
        ->join('diskon', 'diskon.id_diskon', '=', 'detail_diskon.id_diskon')
        ->where('end_date', '>=',"$current_date")
        ->where('kode_menu', $kode)
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

    public function create()
    {
        $this->param['pageInfo'] = 'Daftar Menu';
        $this->param['btnRight']['text'] = 'Lihat Penjualan';
        $this->param['btnRight']['link'] = route('penjualan.index');
        $this->param['kode_penjualan'] = $this->getKode();
        $this->param['kategori'] = KategoriMenu::select('id_kategori_menu','kategori_menu')->get();
        $this->param['menu'] = Menu::where('status', '=', 'Ready')->select('kode_menu','nama','foto','harga_jual')->get();
        
        return view('penjualan.penjualan.tambah-penjualan', $this->param);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode_penjualan' => 'required',
            'nama_customer' => 'required',
            'no_hp' => 'numeric',
            'no_meja' => 'required|numeric',
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

        $total = $totalHarga - $totalDiskon;
        $totalPpn = $total * 10 / 100;

        $newPenjualan = new Penjualan;
        $newPenjualan->kode_penjualan = $request->get('kode_penjualan');
        $newPenjualan->nama_customer = $request->get('nama_customer');
        $newPenjualan->no_hp = $request->get('no_hp');
        $newPenjualan->no_meja = $request->get('no_meja');
        $newPenjualan->jenis_order = $request->get('jenis_order');
        $newPenjualan->jumlah_item = count($_POST['kode_menu']);
        $newPenjualan->jumlah_qty = $ttlQty;
        $newPenjualan->total_harga = $totalHarga; // harga sebelum diskon dan ppn
        $newPenjualan->total_ppn = $totalPpn;
        $newPenjualan->waktu = date('Y-m-d H:i:s');
        $newPenjualan->jumlah_qty = $ttlQty;
        $newPenjualan->total_diskon = $totalDiskon;

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
        return redirect()->route('penjualan.index')->withStatus('Data berhasil ditambahkan.');
    }

    public function show($id)
    {
        //
    }

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
        $this->param['pageInfo'] = 'Pembayaran';
        $this->param['btnRight']['text'] = 'Pembayaran';
        $this->param['btnRight']['link'] = route('pembayaran');

        $penjualan = Penjualan::findOrFail($kode);
        $detail = DetailPenjualan::where('kode_penjualan', $kode)->get();

        return view('penjualan.pembayaran.form-pembayaran', ['penjualan' => $penjualan, 'detail' => $detail], $this->param);
    }

    public function savePembayaran(Request $request, $kode)
    {
        $penjualan = Penjualan::findOrFail($kode);
        $validatedData = $request->validate([
            'bayar' => 'required|numeric|gte:grand_total',
            'jenis_bayar' => 'required'
        ]);

        $penjualan->jenis_bayar = $request->get('jenis_bayar');
        $penjualan->no_kartu = $request->get('no_kartu');
        $penjualan->status_bayar = 'Sudah Bayar';
        $penjualan->total_diskon_tambahan = $request->get('diskon_tambahan');
        $penjualan->bayar = $request->get('bayar');
        $penjualan->kembalian = $request->get('kembalian');

        $penjualan->save();

        return redirect()->route('pembayaran')->withStatus('Pembayaran berhasil.');    
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
        $filter = $menu->get();

        return view('penjualan.penjualan.loop-menu', ['menu' => $filter]);
    }

    public function laporanPenjualan()
    {
        $this->param['pageInfo'] = 'Laporan Pembayaran';
        $this->param['btnRight']['text'] = 'Tambah Penjualan';
        $this->param['btnRight']['link'] = route('penjualan.create');
        if(isset($_GET['dari']) && isset($_GET['sampai'])){
            if($_GET['tipe']=='general'){
                $this->param['penjualan'] = Penjualan::where('status_bayar','Sudah Bayar')->whereBetween('waktu',[$_GET['dari'],$_GET['sampai']])->orderBy('waktu','asc')->get();
            }
            else if($_GET['tipe']=='menu-favorit'){
                $this->param['menu'] = \DB::table('menu as m')->select(\DB::raw('m.kode_menu,m.nama, SUM(dp.qty) as qty, sum(sub_total) as total'))->leftJoin('detail_penjualan as dp','m.kode_menu','dp.kode_menu')->join('penjualan as p', 'dp.kode_penjualan','p.kode_penjualan')->where('p.status_bayar','Sudah Bayar')->whereBetween('p.waktu',[$_GET['dari'],$_GET['sampai']])->groupBy('m.kode_menu')->orderBy('qty','desc')->orderBy('total','desc')->get();
            }
            else if($_GET['tipe']=='tidak-terjual'){
                $this->param['menu'] = \DB::table('menu as m')->select('m.kode_menu','m.nama','m.hpp','m.harga_jual','m.status')->whereNotIn('m.kode_menu', function($query){
                    $query->select('dp.kode_menu')->from('detail_penjualan as dp')->join('penjualan as p','dp.kode_penjualan','p.kode_penjualan')->where('p.status_bayar','Sudah Bayar')->whereBetween('p.waktu',[$_GET['dari'],$_GET['sampai']]);
                })->orderBy('m.kode_menu','asc')->get();
            }
        }
        return view('penjualan.laporan.laporan-penjualan', $this->param);
    }
}
