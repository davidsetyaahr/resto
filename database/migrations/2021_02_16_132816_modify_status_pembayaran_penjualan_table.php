<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyStatusPembayaranPenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE penjualan MODIFY COLUMN status_bayar ENUM('Belum Bayar', 'Sudah Bayar', 'Piutang')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE penjualan MODIFY COLUMN status_bayar ENUM('Belum Bayar', 'Sudah Bayar')");
    }
}
