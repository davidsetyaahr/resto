<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoomOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE penjualan MODIFY COLUMN jenis_order ENUM('Dine In', 'Take Away', 'Room Order')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE penjualan MODIFY COLUMN jenis_order ENUM('Dine In', 'Take Away')");
    }
}
