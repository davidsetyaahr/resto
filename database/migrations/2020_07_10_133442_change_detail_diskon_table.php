<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDetailDiskonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detail_diskon', function (Blueprint $table) {
            $table->dropForeign(['kode_menu']);
            $table->dropColumn('kode_menu');
            $table->tinyInteger('id_kategori_menu')->unsigned()->nullable()->after('id_diskon');

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
        Schema::table('detail_diskon', function (Blueprint $table) {
            $table->string('kode_menu', 10);
            $table->dropForeign(['id_kategori_menu']);
            $table->dropColumn('id_kategori_menu');
            $table->foreign('kode_menu')
                    ->references('kode_menu')
                    ->on('menu');
        });
    }
}
