<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPembelianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_pembelian', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_pembelian', 15);
            $table->string('kode_barang', 10);
            $table->smallInteger('qty');
            $table->integer('subtotal');
            $table->timestamps();

            $table->foreign('kode_pembelian')
                    ->references('kode_pembelian')
                    ->on('pembelian');

            $table->foreign('kode_barang')
                    ->references('kode_barang')
                    ->on('barang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_pembelian');
    }
}
