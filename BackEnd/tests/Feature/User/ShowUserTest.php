<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ShowUserTest extends TestCase
{
    use DatabaseTransactions;

    const ROUTE = self::BASE_ROUTE . 'user/show/%s';

    public function testShowUserSuccess()
    {
        $this->makeEmployee();

        $this
            ->actingAs(User::factory()->create())
            ->json('GET', sprintf(self::ROUTE, $this->employee->id))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'name',
                    'email',
                    'phone',
                    'id',
                    'type',
                    'about_me',
                    'profile_picture_path'
                ]
            ]);
    }
    //todo check if user exists exception
}
