<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldIdGrupKategoriToKategoriMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kategori_menu', function (Blueprint $table) {
            $table->foreignId('id_grup_kategori')->after('id_kategori_menu');
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
            $table->dropForeign('kategori_menu_id_grup_kategori');
            $table->dropColumn('id_grup_kategori');
        });
    }
}
