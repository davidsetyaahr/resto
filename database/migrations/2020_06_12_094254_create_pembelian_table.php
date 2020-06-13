<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembelianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembelian', function (Blueprint $table) {
            $table->string('kode_pembelian', 15);
            $table->date('tanggal');
            $table->tinyInteger('jumlah_item');
            $table->smallInteger('jumlah_qty');
            $table->integer('total');
            $table->timestamps();

            $table->primary('kode_pembelian');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembelian');
    }
}
