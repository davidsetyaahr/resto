<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Penjualan;

class MainController extends Controller
{
    public function getTotalByDate($date)
    {
        $status = null;
        $msg = null;
        $penjualan = null;
        try{
            if($date == null){
                $status = 'Failed';
                $msg = 'Date is empty';
            }
            elseif($date == 'null'){
                $status = 'Failed';
                $msg = 'Date is empty';
            }         
            else{
                $penjualan = Penjualan::select(
                    \DB::raw('SUM(penjualan.total_harga - penjualan.total_diskon - penjualan.total_diskon_tambahan) AS total'),
                    \DB::raw('SUM(penjualan.total_ppn) AS total_ppn'),
                    'jenis_bayar'
                )
                ->whereBetween('penjualan.waktu', [$date.' 00:00:00', $date.' 23:59:59'])  
                ->where('penjualan.status_bayar', 'Sudah Bayar')
                ->groupBy('jenis_bayar')
                ->get();
                $status = 'Success';
                $msg = 'Successfully';
            }
        }
        catch(Exception $e){
            $status = 'Error';
            $msg = $e->getMessage();  
        }
        catch(\Illuminate\Database\QueryException $e){
            $status = 'Database Error';
            $msg = $e->getMessage();
        }
        finally{
            $response = [
                'status' => $status,
                'message' => $msg,
                'data' => $penjualan
            ];
            return $response;
        }
    }

    public function getPiutang($kodePenjualan)
    {
        $status = null;
        $msg = null;
        $penjualan = null;

        try{;
            $where['penjualan.status_bayar'] = 'Piutang';
            if(isset($_GET['kode_penjualan'])){
                $where['penjualan.kode_penjualan'] = $kodePenjualan;
            }
            $penjualan = Penjualan::select(
                'penjualan.kode_penjualan',
                'penjualan.nama_customer',
                DB::raw('penjualan.total_harga - (penjualan.total_diskon + penjualan.total_diskon_tambahan) AS total'),
                DB::raw('penjualan.total_ppn AS total_ppn'),
                'penjualan.waktu'
            )
            ->where('penjualan.status_bayar', 'Piutang')
            ->where('penjualan.kode_penjualan', $kodePenjualan)
            ->get();
                
            $status = count($penjualan)==0 ? 'Kosong' : 'Success';
            $msg = 'Successfully';
        }
        catch(Exception $e){
            $status = 'Error';
            $msg = $e->getMessage();  
        }
        catch(\Illuminate\Database\QueryException $e){
            $status = 'Database Error';
            $msg = $e->getMessage();
        }
        finally{
            $response = [
                'status' => $status,
                'message' => $msg,
                'data' => $penjualan
            ];
            return $response;
        }
    }

    public function getListPiutang()
    {
        $status = null;
        $msg = null;
        $penjualan = null;

        // $penjualan = Penjualan::select(
        //     'penjualan.kode_penjualan',
        //     'penjualan.nama_customer',
        //     DB::raw('penjualan.total_harga - (penjualan.total_diskon + penjualan.total_diskon_tambahan) AS total'),
        //     DB::raw('penjualan.total_ppn AS total_ppn'),
        //     'penjualan.waktu'
        // )
        // ->where('penjualan.status_bayar', 'Piutang')
        // ->get();

        // $response = [
        //     'status' => 's',
        //     'message' => '$msg',
        //     'data' => $penjualan
        // ];
        // return $response;
        
        try{
            $penjualan = Penjualan::select(
                'penjualan.kode_penjualan',
                'penjualan.nama_customer',
                DB::raw('penjualan.total_harga - (penjualan.total_diskon + penjualan.total_diskon_tambahan) AS total'),
                DB::raw('penjualan.total_ppn AS total_ppn'),
                'penjualan.waktu'
            )
            ->where('penjualan.status_bayar', 'Piutang')
            ->get();          
                
            $status = count($penjualan)==0 ? 'Kosong' : 'Success';
            $msg = 'Successfully';
        }
        catch(Exception $e){
            $status = 'Error';
            $msg = $e->getMessage();  
        }
        catch(\Illuminate\Database\QueryException $e){
            $status = 'Database Error';
            $msg = $e->getMessage();
        }
        finally{
            $response = [
                'status' => $status,
                'message' => $msg,
                'data' => $penjualan
            ];
            return $response;
        }
    }

    public function bayarPiutang(\Illuminate\Http\Request $req)
    {
        $status = null;
        $msg = null;
        try{
            if(isset($_POST['kode_transaksi'])){
                DB::table('penjualan')->where('kode_penjualan', $req->get('kode_transaksi'))->where('status_bayar', 'Piutang')->update(['status_bayar' => 'Piutang Terbayar']);

                $status = 'Success';
                $msg = 'Successfully';
            }
            else{
                $status = 'Failed';   
                $msg = 'Kode transaksi is empty';
            }
        }
        catch(Exception $e){
            $status = 'Error';
            $msg = $e->getMessage();  
        }
        catch(\Illuminate\Database\QueryException $e){
            $status = 'Database Error';
            $msg = $e->getMessage();
        }
        finally{
            $response = [
                'status' => $status,
                'message' => $msg
            ];
            return json_encode($response);
        }
    }
}
