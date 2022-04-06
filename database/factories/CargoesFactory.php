<?php

namespace Database\Factories;

use App\Models\Currents;
use Illuminate\Database\Eloquent\Factories\Factory;

class CargoesFactory extends Factory
{

    public function definition() {
        $currnetSender = Currents::inRandomOrder()->first();
        $currentReceiver = Currents::inRandomOrder()->first();
        return [
            'receiver_id' => $currentReceiver->id,
            'receiver_name' => $currentReceiver->name,
            'receiver_phone' => $currentReceiver->phone ?? $this->faker->phoneNumber(),
            'receiver_address' => $currentReceiver->city,
            'sender_id' => $currnetSender->id,
            'sender_name' => $currnetSender->name,
            'sender_phone' => $currnetSender->phone ?? $this->faker->phoneNumber(),
            'payment_type' => 'Alıcı Ödemeli',
            'cargo_type' => 'Koli',
            'cargo_content' => $this->faker->paragraph(1),
            'tracking_no' => $this->faker->randomNumber(6, 1000000000),
            'arrival_city' => 'Istanbul',
            'arrival_district' => 'Esenyurt',
            'arrival_agency_code' => 93,
            'arrival_tc_code' => 96,
            'departure_city' => 'Ankara',
            'departure_district' => 'adsd',
            'departure_agency_code' => 23,
            'departure_tc_code' => 52,
            'creator_agency_code' => 56,
            'creator_user_id' => 45,
            'status' => 'married',
            'collection_payment_type' => 'Nakit',
            'number_of_pieces' => 0,
            'desi' => 10,
            'kdv_price' => 10,
            'distance_price' => 10,
            'mobile_service_price' => 15,
            'service_price' => 45,
            'add_service_price' => 74,
            'post_service_price' => 56,


        ];
    }
}
