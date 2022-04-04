<?php

namespace Database\Factories;

use App\Models\TcCars;
use App\Models\TransferCars;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TcCarsFactory extends Factory
{
    protected $model = TcCars::class;

    public function definition()
    {
        return [
            "car_type" => "Aktarma",
            "marka" => "BMC 415 PRO",
            "model" => "Sidan",
            "model_yili" => "2000",
            "plaka" => $this->faker->randomNumber(6,100000),
            'confirm' => 1,
            'confirmed_user' => User::where('email', 'j.shukurow@cumhuriyetkargo.com.tr')->first()->value('id'),
            'confirmed_date' => now(),
            'branch_code' => null,
            "status" => 0,
        ];
    }
}
