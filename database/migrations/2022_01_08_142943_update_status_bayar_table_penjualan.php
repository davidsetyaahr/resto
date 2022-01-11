<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusBayarTablePenjualan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penjualan', function (Blueprint $table) {
            \DB::statement("ALTER TABLE `penjualan` CHANGE `status_bayar` `status_bayar` ENUM('Belum Bayar', 'Sudah Bayar', 'Piutang','Piutang Terbayar') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penjualan', function (Blueprint $table) {
            //
        });
    }
}
