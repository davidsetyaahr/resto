<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePenjualan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->dateTime('waktu');
            $table->integer('jumlah_qty');
            $table->integer('total_diskon')->default(0);
            $table->integer('total_diskon_tambahan')->default(0);
            $table->dropColumn('tanggal_penjualan');
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
            $table->dropColumn('waktu');
            $table->dropColumn('jumlah_qty');
            $table->dropColumn('total_diskon');
            $table->dropColumn('total_diskon_tambahan');
            $table->date('tanggal_penjualan');
        });
    }
}
