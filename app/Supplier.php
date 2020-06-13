<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table='supplier';
    protected $primaryKey = 'kode_supplier';
    protected $keyType = 'string';
}
