<?php

namespace Tests\Feature\Expeditions;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReadCargoTest extends TestCase
{
    public function test_Status_One()
    {
        $user = User::where('email', 'sistem@ckgsis.com')->first();
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
        $user = User::where('email', 'sistem@ckgsis.com')->first();
        $response = $this->actingAs($user, 'api')->post('/api/Expedition/Read', ['plaka' => '#####PlakaYok']);

        $response->assertJson(['status' => 0]);


    }
}
