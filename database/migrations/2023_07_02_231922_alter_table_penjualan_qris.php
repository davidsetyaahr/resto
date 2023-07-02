<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePenjualanQris extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penjualan', function (Blueprint $table) {
            \DB::statement("ALTER TABLE `penjualan` CHANGE `jenis_bayar` `jenis_bayar` ENUM('Tunai','Debit BCA','Debit BRI','Kredit BCA','Kredit BRI','Debit Bank Lain','Kredit Bank Lain','QRIS') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
