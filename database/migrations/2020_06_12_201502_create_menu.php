<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->string('kode_menu', 10);
            $table->tinyInteger('id_kategori_menu')->unsigned()->nullable();
            $table->string('nama',30);
            $table->integer('hpp');
            $table->integer('harga_jual');
            $table->text('foto');
            $table->enum('status', ['Habis', 'Ready']);
            $table->timestamps();

            $table->primary('kode_menu');
            $table->foreign('id_kategori_menu')
                    ->references('id_kategori_menu')
                    ->on('kategori_menu');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu');
    }
}
