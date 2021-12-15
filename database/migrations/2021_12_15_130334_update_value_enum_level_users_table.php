<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateValueEnumLevelUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        \DB::statement("ALTER TABLE `users` CHANGE `level` `level` ENUM('Accounting', 'Kasir', 'Owner', 'Waiters','Resepsionis') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL");    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
