<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerusahaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perusahaan', function (Blueprint $table) {
            $table->tinyIncrements('id_perusahaan');
            $table->string('nama', 30);
            $table->char('inisial', 5)->nullable();
            $table->string('npwp', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->char('kota', 15)->nullable();
            $table->text('alamat_kantor')->nullable();
            $table->string('telepon', 13)->nullable();
            $table->string('email', 30)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perusahaan');
    }
}
