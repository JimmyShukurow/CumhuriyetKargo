<?php

namespace Database\Factories;

use App\Models\TransferCars;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransferCarsFactory extends Factory
{

    public function definition()
    {
        return [
            "car_type" => "Aktarma",
            "marka" => "BMC 415 PRO",
            "model" => "Sidan",
            "model_yili" => "2000",
            "plaka" => $this->faker->randomNumber(10),
            "status" => 0
        ];
    }
}
