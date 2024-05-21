<?php

namespace Tests\Feature\Job;

use App\Domain\Job\Enum\JobIncludesEnum;
use App\Models\Competence;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ShowJobTest extends TestCase
{
    use DatabaseTransactions;

    const ROUTE = self::BASE_ROUTE . 'job/%s';

    public function testShowJobSuccess()
    {
        $job = Job::factory()->create();

        $this
            ->actingAs(User::factory()->create())
            ->json('GET', sprintf(self::ROUTE, $job->id))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
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
            ]);
    }

    public function testShowJobWithCompanyInclude()
    {
        $job = Job::factory()->create();

        $this
            ->actingAs(User::factory()->create())
            ->json(
                'GET',
                sprintf(self::ROUTE, $job->id),
                [
                    'includes' => [
                        JobIncludesEnum::Company->value
                    ]
                ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
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
            ]);
    }

    public function testShowJobWithCompetences()
    {
        $job = Job::factory()
            ->has(Competence::factory()->count(3))
            ->create();

        $this
            ->actingAs(User::factory()->create())
            ->json(
                'GET',
                sprintf(self::ROUTE, $job->id),
                [
                    'includes' => [
                        JobIncludesEnum::Competences->value
                    ]
                ])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
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
            ]);
    }
    //todo check if job exists exception
    public function testJobExistsValidation()
    {
        $jobId = Job::max('id') + 1;
        $this->makeRecruiter();

        $this
            ->actingAs($this->employee)
            ->json('GET', sprintf(self::ROUTE, $jobId))
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                'message',
                'additional_info' => [
                    'job_id'
                ]
            ])->assertJson([
                'message' => 'Job not found',
                'additional_info' => [
                    'job_id' => $jobId
                ]
            ]);
    }
}
