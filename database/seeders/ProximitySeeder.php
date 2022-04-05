<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProximitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = [
            'Kendisi',
            'Komşusu',
            'Eşi',
            'Akrabası',
            'Arkadaşı',
            'Kardeşi',
            'Baba',
            'Anne',
            'Diğer',
        ];

        foreach ($array as $key)
            DB::table('proximity_degrees')->insert([
                'proximity' => $key,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
    }
}
