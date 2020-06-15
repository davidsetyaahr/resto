<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KasKeluar extends Model
{
    protected $table = 'kas_keluar';
    protected $primaryKey = 'kode_kas';

    public $incrementing = false;
}
