<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailPemakaianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_pemakaian', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_pemakaian', 15);
            $table->string('kode_barang', 10);
            $table->smallInteger('qty');
            $table->integer('subtotal_saldo');
            $table->text('keterangan');
            $table->timestamps();

            $table->foreign('kode_pemakaian')
                    ->references('kode_pemakaian')
                    ->on('pemakaian');

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
        Schema::dropIfExists('detail_pemakaian');
    }
}
