<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('modules')->insert([
            'name' => 'Kargolar',
            'ico' => 'pe-7s-box2',
            'module_group_id' => 1,
            'link' => 'kargo_link',
            'description' => 'This is a generel cargo module.'
        ]);
    }
}
