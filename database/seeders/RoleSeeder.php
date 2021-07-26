<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'admin',
            'display_name' => 'Genel Yönetici',
            'description' => 'Genel Yönetici Tüm Sistemde Erişim Yetkisi Vardır.'
        ]);
    }
}
