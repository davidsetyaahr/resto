<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'kode_barang';
    public $incrementing = false;

    public function kategori()
    {
        return $this->belongsTo('App\KategoriBarang', 'id_kategori_barang');
    }
}
