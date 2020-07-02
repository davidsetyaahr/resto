<?php

use Illuminate\Database\Seeder;

class AdministratorSeeder extends Seeder
{
    public function run()
    {
        $administrator = new \App\User;
        $administrator->nama = 'Administrator';
        $administrator->username = 'Administrator';
        $administrator->gender = 'Laki-laki';
        $administrator->email = 'administrator@baratha.com';
        $administrator->no_hp = '-';
        $administrator->alamat = '-';
        $administrator->password = \Hash::make('administrator');
        $administrator->level = 'Owner';
        $administrator->save();
    }
}
