<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->integer('stock')->default(0)->change();
            $table->integer('minimum_stock')->default(0)->change();
            $table->integer('saldo')->default(0)->change();
            $table->date('exp_date')->nullable()->change();
            $table->text('keterangan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->integer('stock')->change();
            $table->integer('minimum_stock')->change();
            $table->integer('saldo')->change();
            $table->date('exp_date')->change();
            $table->text('keterangan')->change();
        });
    }
}
