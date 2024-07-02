<?php

namespace Tests\Feature\Company;

use App\Models\Company;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ShowCompanyTest extends TestCase
{
    use DatabaseTransactions;

    const ROUTE = self::BASE_ROUTE . 'company/%s';

    public function testShow()
    {
        $this->makeEmployee();

        /** @var Company $company */
        $company = Company::factory()->create();

        $this
            ->actingAs($this->employee)
            ->get(sprintf(self::ROUTE, $company->id))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'phone',
                    'email',
                    'fantasy_name',
                ],
            ])
            ->assertJson([
                'data' => [
                    'id' => $company->id,
                    'name' => $company->name,
                    'description' => $company->description,
                    'phone' => $company->phone,
                    'email' => $company->email,
                    'fantasy_name' => $company->fantasy_name,
                ]
            ]);

    }
}
