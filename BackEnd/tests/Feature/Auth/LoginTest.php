<?php

namespace Tests\Feature\Auth;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LoginTest extends TestCase
{
    const ROUTE = self::BASE_ROUTE . 'auth/login';


    public function testNonexistentEmail()
    {
        $payload = $this->generatePayload();

        $this->json('POST', self::ROUTE, $payload)->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'errors' => ['email']
            ])
            ->assertJson([
                'message' => 'User not registered',
                'errors' => [
                    'email' => [
                        'User not registered'
                    ]
                ]
            ]);
    }

    /**
     * @return array{
     *      password: string,
     *      email: string,
     * }
     */
    public function generatePayload(): array
    {
        return [
            'password' => $this->faker->password,
            'email' => $this->faker->unique()->safeEmail,
        ];
    }
}
