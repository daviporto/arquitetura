<?php

namespace Tests\Feature\Job;

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
    //todo check if user exists exception
}