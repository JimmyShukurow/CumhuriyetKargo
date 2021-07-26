<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name_surname' => 'NURULLAH GÜÇ',
            'email' => 'n.guc@ckgteam.com',
            'password' => Hash::make('CKG1416!'),
            'role_id' => 1,
            'phone' => '5354276824'
        ]);
    }
}
