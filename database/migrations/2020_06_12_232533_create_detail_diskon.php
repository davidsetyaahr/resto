<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailDiskon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_diskon', function (Blueprint $table) {
            $table->increments('id_detail_diskon');
            $table->integer('id_diskon')->nullable()->unsigned();
            $table->string('kode_menu', 10);
            $table->timestamps();

            $table->foreign('id_diskon')
                    ->references('id_diskon')
                    ->on('diskon');
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
        Schema::dropIfExists('detail_diskon');
    }
}
