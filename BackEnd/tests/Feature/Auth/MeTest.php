<?php

namespace Tests\Feature\Auth;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class MeTest extends TestCase
{
    const ROUTE = self::BASE_ROUTE . 'auth/me';

    public function testNonexistentEmail()
    {
        $this->makeEmployee();

        $this->actingAs($this->employee)
            ->json('GET', self::ROUTE)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'name',
                    'email',
                    'phone',
                    'id',
                    'type',
                    'about_me'
                ],
            ])
            ->assertJson([
                'data' => [
                    'name' => $this->employee->name,
                    'email' => $this->employee->email,
                    'phone' => $this->employee->phone,
                    'type' => $this->employee->type,
                    'id' => $this->employee->id,
                    'about_me' => $this->employee->about_me,
                    'profile_picture' => $this->employee->profile_picture_path
                ],
            ]);
    }
}
