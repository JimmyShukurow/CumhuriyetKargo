<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sub_modules')->insert([
           'module_id' => 1,
            'sub_name' => 'Ana Menü',
            'must' => 0,
            'link' => 'Kargo.Anamenu',
        ]);
    }
}
