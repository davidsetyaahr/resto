<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldIsPaket extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kategori_menu', function (Blueprint $table) {
            $table->enum('is_paket',['0','1'])->default('0')->after('kategori_menu');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kategori_menu', function (Blueprint $table) {
            $table->dropColumn('is_paket');
        });
    }
}
