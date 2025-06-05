<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrupKategori extends Model
{
    protected $table = 'grup_kategori';
    protected $primaryKey = 'id_grup_kategori';

    public function kategori_menu()
    {
        return $this->hasMany('App\KategoriMenu', 'id_grup_kategori', 'id_grup_kategori');
    }
}
