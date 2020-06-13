<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenjualan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->string('kode_penjualan', 15);
            $table->date('tanggal_penjualan');
            $table->char('nama_customer', 15);
            $table->string('no_hp', 13)->nullable();
            $table->tinyInteger('no_meja');
            $table->enum('jenis_order', ['Dine In', 'Take Away']);
            $table->tinyInteger('jumlah_item');
            $table->enum('jenis_bayar', ['Debit', 'Tunai']);
            $table->string('no_kartu', 16)->nullable();
            $table->enum('status_bayar', ['Belum Bayar', 'Sudah Bayar']);
            $table->integer('total_harga')->unsigned();
            $table->integer('total_ppn')->unsigned();
            $table->timestamps();

            $table->primary('kode_penjualan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penjualan');
    }
}
