<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropPaketMenuTanpaPpn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('paket_menu_tanpa_ppn');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('paket_menu_tanpa_ppn', function (Blueprint $table) {
            $table->id();
            $table->string('kode_menu', 10);
            $table->timestamps();
        });
    }
}
