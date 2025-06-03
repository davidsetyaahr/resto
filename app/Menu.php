<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'kode_menu';
    protected $guarded = [];
    public $incrementing = false;

    public function kategori()
    {
        return $this->belongsTo('App\KategoriMenu', 'id_kategori_menu');
    }
}
