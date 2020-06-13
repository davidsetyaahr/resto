<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailPemakaian extends Model
{
    protected $table='detail_pemakaian';

    public function pemakaian()
    {
        return $this->belongsTo('App\Pemakaian', 'kode_pemakaian');
    }
}
