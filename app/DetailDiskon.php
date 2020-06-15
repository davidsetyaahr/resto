<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailDiskon extends Model
{
    protected $table = 'detail_diskon';

    public function diskon()
    {
        return $this->belongsTo('App\Diskon', 'id_diskon');
    }
}
