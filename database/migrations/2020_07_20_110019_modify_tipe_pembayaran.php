<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTipePembayaran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE penjualan MODIFY COLUMN jenis_bayar ENUM('Tunai', 'Debit BCA', 'Debit BRI', 'Kredit BCA', 'Kredit BRI')");
        Schema::table('penjualan', function (Blueprint $table) {
            $table->integer('charge');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE penjualan MODIFY COLUMN jenis_bayar ENUM('Tunai', 'Debit')");

        Schema::table('penjualan', function (Blueprint $table) {
            $table->dropColumn('charge');
        });
    }
}
