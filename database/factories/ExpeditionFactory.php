<?php

namespace Database\Factories;

use App\Models\TcCars;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;

class ExpeditionFactory extends Factory
{

    public function definition()
    {
        return [
            'serial_no' => $this->faker->randomNumber(9, 100000000),
            'car_id' => TcCars::inRandomOrder()->first()->value('id'),
            'user_id' => User::where('email', 'sistem@ckgsis.com')->first()->value('id'),
            'description' => $this->faker->paragraph(1),
            'done' => 0
        ];
    }
}
