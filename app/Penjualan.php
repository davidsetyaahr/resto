<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'kode_penjualan';

    public $incrementing = false;

    public function detail_penjualan()
    {
        return $this->hasMany('App\DetailPenjualan');
    }
}
