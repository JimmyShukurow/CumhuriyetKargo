<?php

namespace Tests\Feature\Expeditions;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReadExpeditionTest extends TestCase
{
    public function getUser()
    {
        return User::where('email', 'sistem@ckgsis.com')->first();
    }
    public function test_Status_One()
    {
        $user = $this->getUser();
        $response = $this->actingAs($user, 'api')->post('/api/Expedition/Read', ['plaka' => '34GM18']);

        $response->assertStatus(200);
        $response->assertJsonStructure(
            [
                "status",
                "expedetion" => [
                    "id",
                    "plaque",
                    "expedition_no",
                    "driver_name",
                    "departure_branch",
                    "arrival_branch",
                    "cargo_count",
                    "inbetweens" => [
                        [
                            "id",
                            "branch"
                        ]
                    ],
                ],
                "cargoes" => [
                    [
                        "tracking_no_crypted",
                        "receiver_name",
                        "receiver_address",
                        "tracking_no",
                        "number_of_pieces",
                        "part_no",
                    ]
                ]
            ]
        );
    }

    public function test_Status_Zero_Wrong_Plaque()
    {
        $user = $this->getUser();
        $response = $this->actingAs($user, 'api')->post('/api/Expedition/Read', ['plaka' => '#####PlakaYok']);

        $response->assertJson(['status' => 0, 'message' => 'Bu araca sefer oluşturulmamış!']);
    }

    public function test_Status_Zero_Empty_Plaque()
    {
        $user = $this->getUser();
        $response = $this->actingAs($user, 'api')->post('/api/Expedition/Read', ['plaka' => '']);

        $response->assertJson(['status' => 0, 'message' => 'Plaka alanı zorunludur!']);
    }


}
