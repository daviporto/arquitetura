<?php

namespace Tests\Feature\Company;

use App\Domain\User\UserTypeEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CreateCompanyTest extends TestCase
{
    use DatabaseTransactions;

    const ROUTE = self::BASE_ROUTE . 'company';


    public function testCreateSuccess()
    {
        $payload = $this->generatePayload();
        $response = 
        $this
            ->actingAs($this->generateRecruiterUser())
            ->json('POST', self::ROUTE, $payload);
            print_r($response->getContent());
    $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'phone',
                    'email',
                    'fantasy_name',
                ]
            ])
            ->assertJson([
                'data' => [
                    'name' => $payload['name'],
                    'description' => $payload['description'],
                    'phone' => $payload['phone'],
                    'email' => $payload['email'],
                    'fantasy_name' => $payload['fantasy_name'],
                ]
            ]);

        $this->assertDatabaseHas('companies', [
            'name' => $payload['name'],
            'description' => $payload['description'],
            'phone' => $payload['phone'],
            'email' => $payload['email'],
            'cnpj' => $payload['cnpj'],
            'fantasy_name' => $payload['fantasy_name'],
        ]);
    }

    /**
     * @return array{
     *      name: string,
     *      password: string,
     *      email: string,
     *      phone: string
     * }
     */

    public function generatePayload(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->email,
            'cnpj' => $this->faker->cnpj(false),
            'fantasy_name' => $this->faker->name,
        ];
    }

    private function generateRecruiterUser(): User
    {
        return User::factory()->create([
            'type' => UserTypeEnum::Recruiter->value
        ]);
    }

}
