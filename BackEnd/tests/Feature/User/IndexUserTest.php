<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class IndexUserTest extends TestCase
{
    use DatabaseTransactions;

    const ROUTE = self::BASE_ROUTE . 'user';

    public function testIndexUserSuccess()
    {
        User::factory(3)->create();

        $this
            ->actingAs(User::factory()->create())
            ->json('GET', self::ROUTE)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'name',
                        'email',
                        'phone',
                        'id',
                        'type',
                        'about_me'
                    ]
                ]
            ]);
    }
}
