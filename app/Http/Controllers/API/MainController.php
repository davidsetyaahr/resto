<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
                    \DB::raw('SUM(penjualan.total_harga - penjualan.total_diskon) AS total'),
                    \DB::raw('SUM(penjualan.total_ppn) AS total_ppn')
                )
                ->whereBetween('penjualan.waktu', [$date.' 00:00:00', $date.' 23:59:59'])  
                ->where('penjualan.status_bayar', 'Sudah Bayar')
                ->first();
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
            return json_encode($response);
        }
    }
}
