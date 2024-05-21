<?php

namespace Tests\Feature\Job;

use App\Domain\Job\Enum\JobIncludesEnum;
use App\Models\Competence;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class IndexJobTest extends TestCase
{
    use DatabaseTransactions;

    const ROUTE = self::BASE_ROUTE . 'job';

    public function testIndexJobSuccess()
    {
        Job::factory(5)->create();

        $this
            ->actingAs(User::factory()->create())
            ->json('GET', self::ROUTE)
            ->assertJsonCount(5, 'data')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'is_available',
                        'applications_amount',
                        'salary',
                        'salary_time_unit',
                        'accept_application_until',
                        'work_model',
                        'employment_type',
                        'week_workload',
                        'location',
                        'company_id',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ]);
    }

    public function testShowJobWithCompanyInclude()
    {
        Job::factory(5)->create();

        $this
            ->actingAs(User::factory()->create())
            ->json(
                'GET',
                sprintf(self::ROUTE),
                [
                    'includes' => [
                        JobIncludesEnum::Company->value
                    ]
                ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'is_available',
                        'applications_amount',
                        'salary',
                        'salary_time_unit',
                        'accept_application_until',
                        'work_model',
                        'employment_type',
                        'week_workload',
                        'location',
                        'company_id',
                        'created_at',
                        'updated_at',
                        'company' => [
                            'id',
                            'name',
                            'description',
                            'phone',
                            'email',
                            'cnpj',
                            'fantasy_name',
                            'created_at',
                            'updated_at',
                        ]
                    ]
                ]
            ]);
    }

    public function testShowJobWithCompetencesInclude()
    {
        Job::factory(3)
            ->has(Competence::factory()->count(3))
            ->create();

        $this
            ->actingAs(User::factory()->create())
            ->json(
                'GET',
                sprintf(self::ROUTE),
                [
                    'includes' => [
                        JobIncludesEnum::Competences->value
                    ]
                ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'is_available',
                        'applications_amount',
                        'salary',
                        'salary_time_unit',
                        'accept_application_until',
                        'work_model',
                        'employment_type',
                        'week_workload',
                        'location',
                        'company_id',
                        'created_at',
                        'updated_at',
                        'competences' => [
                            '*' => [
                                'name',
                                'description',
                                'created_at',
                                'updated_at',
                                'id'
                            ]
                        ]
                    ]
                ]
            ]);
    }

    public function testEmptyJobsSuccess()
    {
        $this
            ->actingAs(User::factory()->create())
            ->json('GET', self::ROUTE)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(0, 'data');
    }
}
