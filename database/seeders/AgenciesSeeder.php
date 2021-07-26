<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AgenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('agencies')->insert([
            'name_surname' => 'NURULLAH GÜÇ',
            'city' => 'İSTANBUL',
            'district' => 'ŞİŞLİ',
            'neighbourhood' => 'GÜLBAHAR MAH.',
            'agency_name' => 'GENEL MERKEZ',
            'adress' => 'Gülbahar Mah. Cemal Sururi Sk. Halim Meriç İş Merkezi No:15/E K:4/22 Şişli/İstanbul',
            'phone' => '5354276824',
            'transshipment_center_code' => 1,
            'agency_code' => '21236'
        ]);
    }
}
