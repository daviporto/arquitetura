<?php

namespace Tests\Feature\Company;

use App\Models\Company;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UpdateCompanyTest extends TestCase
{
    use DatabaseTransactions;

    const ROUTE = self::BASE_ROUTE . 'company/%s';

    public function testUpdateCompany()
    {
        $this->makeEmployee();

        /** @var Company $originalCompany */
        $originalCompany = Company::factory()->create([
            'responsible_id' => $this->employee->id,
        ]);

        $payload = $this->generatePayload() + [
                'id' => $originalCompany->id,
            ];

        $this
            ->actingAs($this->employee)
            ->put(
                sprintf(self::ROUTE, $originalCompany->id),
                $payload
            )
            ->assertStatus(Response::HTTP_OK)
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
            'id' => $originalCompany->id,
            'name' => $payload['name'],
            'description' => $payload['description'],
            'phone' => $payload['phone'],
            'email' => $payload['email'],
            'cnpj' => $payload['cnpj'],
            'fantasy_name' => $payload['fantasy_name'],
        ]);
    }

    private function generatePayload(): array
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

}
