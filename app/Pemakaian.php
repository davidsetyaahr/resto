<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pemakaian extends Model
{
    protected $table = 'pemakaian';
    protected $primaryKey = 'kode_pemakaian';

    public $incrementing = false;
}