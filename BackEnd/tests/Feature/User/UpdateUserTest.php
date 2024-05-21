<?php

namespace Tests\Feature\User;

use App\Models\User;
use Faker\Factory;
use Hash;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    use DatabaseTransactions;

    const ROUTE = self::BASE_ROUTE . 'user';

    public function testUpdateUser()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $payload = $this->getPayload();

        $this
            ->actingAs($user)
            ->json('PUT', self::ROUTE, $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'phone',
                    'type',
                    'about_me'
                ]
            ])
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'name' => $payload['name'],
                    'email' => $payload['email'],
                    'phone' => $payload['phone'],
                    'about_me' => $payload['about_me']
                ]
            ]);


        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => $payload['email'],
            'phone' => $payload['phone'],
            'name' => $payload['name'],
        ]);

        $this->assertTrue(Hash::check($payload['password'], User::find($user->id)->password));
    }

    private function getPayload(): array
    {
        $faker = Factory::create();

        return [
            'name' => $faker->name(),
            'email' => $faker->email(),
            'phone' => $faker->numerify('###########'),
            'password' => Str::random(10),
            'about_me' => $this->faker->paragraph()
        ];
    }
}
