<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KategoriBarang extends Model
{
    protected $table='kategori_barang';

    protected $primaryKey = 'id_kategori_barang';

    public function barang()
    {
        return $this->hasMany('App\Barang');
    }
}
