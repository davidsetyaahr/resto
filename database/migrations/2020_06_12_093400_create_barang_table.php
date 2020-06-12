<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->string('kode_barang', 10);
            $table->tinyInteger('id_kategori_barang')->unsigned()->nullable();
            $table->string('nama', 25);
            $table->string('satuan', 10);
            $table->mediumInteger('stock');
            $table->mediumInteger('minimum_stock');
            $table->integer('saldo');
            $table->date('exp_date');
            $table->text('keterangan');
            $table->timestamps();

            $table->primary('kode_barang');
            $table->foreign('id_kategori_barang')
                    ->references('id_kategori_barang')
                    ->on('kategori_barang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barang');
    }
}
