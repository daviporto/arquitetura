<?php

namespace Tests\Feature\Job;

use App\Domain\Job\Enum\JobIncludesEnum;
use App\Models\Competence;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class IndexWithFiltersJobTest extends TestCase
{
    use DatabaseTransactions;

    const ROUTE = self::BASE_ROUTE . 'job';

    public function testIndexJobSuccess()
    {
        Job::factory(5)->create();

        $this
            ->actingAs(User::factory()->create())
            ->json('GET', self::ROUTE)
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

    public function testJobWithFilters()
    {
        $this->makeRecruiter();

        /** @var Competence $competence */
        $competence = Competence::factory()->create();
        /** @var Job $job */
        $job = Job::factory()
            ->create([
                'user_id' => $this->employee->id,
            ]);

        $job->competences()->attach($competence);

        $payload = $this->createPayloadWithFilters($job);

        $this
            ->actingAs($this->employee)
            ->json('GET', self::ROUTE, $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure($this->getJsonStructure())
            ->assertJson($this->getExpectedJson($job, $competence));
    }

    private function createPayloadWithFilters(Job $job): array
    {
        return [
            'filters' => [
                'name' => $job->name,
                'description' => $job->description,
                'is_available' => $job->is_available,
                'salary_from' => $job->salary,
                'salary_to' => $job->salary,
                'salary_time_units' => [$job->salary_time_unit],
                'accept_application_until' => $job->accept_application_until->format('Y-m-d H:i:s'),
                'work_models' => [$job->work_model],
                'employment_types' => [$job->employment_type],
                'week_workload_from' => $job->week_workload,
                'week_workload_to' => $job->week_workload,
                'location' => $job->location,
                'company_ids' => [$job->company_id],
                'user_ids' => [$job->user_id],
                'competence_ids' => $job->competences->pluck('id')->toArray(),
            ],
            'includes' => [
                JobIncludesEnum::Competences->value,
                JobIncludesEnum::Company->value
            ]
        ];
    }

    private function getJsonStructure(): array
    {
        return [
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
                    ],
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
        ];
    }

    private function getExpectedJson(Job $job, Competence $competence): array
    {
        return [
            'data' => [
                '0' => [
                    'id' => $job->id,
                    'name' => $job->name,
                    'description' => $job->description,
                    'is_available' => (int)$job->is_available,
                    'applications_amount' => $job->applications_amount,
                    'salary' => $job->salary,
                    'salary_time_unit' => $job->salary_time_unit,
                    'accept_application_until' => $job->accept_application_until->format('Y-m-d H:i:s'),
                    'work_model' => $job->work_model,
                    'employment_type' => $job->employment_type,
                    'week_workload' => $job->week_workload,
                    'location' => $job->location,
                    'company_id' => $job->company_id,
                    'competences' => [
                        '0' => [
                            'name' => $competence->name,
                            'description' => $competence->description,
                            'id' => $competence->id
                        ]
                    ],
                    'company' => [
                        'id' => $job->company->id,
                        'name' => $job->company->name,
                        'description' => $job->company->description,
                        'phone' => $job->company->phone,
                        'email' => $job->company->email,
                        'cnpj' => $job->company->cnpj,
                        'fantasy_name' => $job->company->fantasy_name,
                    ]

                ]
            ]
        ];
    }
}
