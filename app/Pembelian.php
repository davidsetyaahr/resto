<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    protected $table='pembelian';

    protected $primaryKey = 'kode_pembelian';
    protected $keyType = 'string';

    public function detail_pembelian()
    {
        return $this->hasMany('App\DetailPembelian');
    }

}
