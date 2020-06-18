<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    protected $table = 'detail_penjualan';

    public function penjualan()
    {
        return $this->belongsTo('App\Penjualan', 'kode_penjualan');
    }
}
