<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPenjualan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->increments('id_detail_penjualan')->unsigned();
            $table->string('kode_penjualan', 15);
            $table->string('kode_menu', 10);
            $table->tinyInteger('jumlah_item');
            $table->enum('status', ['Belum', 'Sudah']);
            $table->integer('sub_total');
            $table->integer('sub_total_ppn');
            $table->text('keterangan');
            $table->timestamps();

            $table->foreign('kode_penjualan')
                    ->references('kode_penjualan')
                    ->on('penjualan');
            $table->foreign('kode_menu')
                    ->references('kode_menu')
                    ->on('menu');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_penjualan');
    }
}
