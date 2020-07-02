<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CustomUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'nama');
            $table->enum('gender', ['Laki-laki', 'Perempuan'])->after('name');
            $table->string('email', 191)->nullable()->change();
            $table->string('no_hp', 13)->after('email');
            $table->text('alamat')->after('no_hp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('gender');
            $table->dropColumn('no_hp');
            $table->dropColumn('alamat');
        });
    }
}
