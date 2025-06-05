<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KategoriMenu extends Model
{
    protected $table = 'kategori_menu';

    protected $primaryKey = 'id_kategori_menu';

    public function menu()
    {
        return $this->hasMany('App\Menu');
    }

    public function grup_kategori()
    {
        return $this->belongsTo('App\GrupKategori', 'id_grup_kategori', 'id');
    }
}
