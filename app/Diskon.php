<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Diskon extends Model
{
    protected $table = 'diskon';
    protected $primaryKey = 'id_diskon';

    public function detail_diskon()
    {
        return $this->hasMany('App\DetailDiskon');
    }
}
