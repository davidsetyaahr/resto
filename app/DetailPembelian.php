<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    protected $table='detail_pembelian';
    protected $primaryKey = 'id';

    public function pembelian()
    {
        return $this->belongsTo('App\Pembelian', 'kode_pembelian');
    }

}
