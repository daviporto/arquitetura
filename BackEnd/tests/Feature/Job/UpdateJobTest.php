<?php

namespace Tests\Feature\Job;

use App\Domain\Job\Enum\EmploymentTypeEnum;
use App\Domain\Job\Enum\SalaryTimeUnitEnum;
use App\Domain\Job\Enum\WorkModelEnum;
use App\Domain\User\UserTypeEnum;
use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UpdateJobTest extends TestCase
{
    use DatabaseTransactions;

    const ROUTE = self::BASE_ROUTE . 'job/%s';

    public function testUpdateJobSuccess()
    {
        $owner = $this->generateRecruiterUser();

        $originalJob = Job::factory()->create([
            'user_id' => $owner->id
        ]);

        $payload = $this->generatePayload() + [
                'id' => $originalJob->id,
            ];

        $this
            ->actingAs($owner)
            ->json('PUT', sprintf(self::ROUTE, $originalJob->id), $payload)
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseHas('jobs', [
            'id' => $originalJob['id'],
            'name' => $payload['name'],
            'description' => $payload['description'],
            'is_available' => $payload['is_available'],
            'applications_amount' => $payload['applications_amount'],
            'salary' => $payload['salary'],
            'salary_time_unit' => $payload['salary_time_unit'],
            'accept_application_until' => $payload['accept_application_until'],
            'work_model' => $payload['work_model'],
            'employment_type' => $payload['employment_type'],
            'week_workload' => $payload['week_workload'],
            'location' => $payload['location'],
            'company_id' => $payload['company_id'],
        ]);
    }

    public function testNonexistentCompany()
    {
        $job = Job::factory()->create();
        $payload = $this->generatePayloadWithNonexistentCompany();

        $this
            ->actingAs($this->generateRecruiterUser())
            ->json('PUT', sprintf(self::ROUTE, $job->id), $payload)
            ->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure([
                'message',
                'additional_info' => [
                    'company_id'
                ]
            ])->assertJson([
                'message' => 'Company not found',
                'additional_info' => [
                    'company_id' => $payload['company_id']
                ]
            ]);
    }

    public function testNonNumericJobId()
    {
        $payload = $this->generatePayload();

        $this
            ->actingAs($this->generateRecruiterUser())
            ->json('PUT', sprintf(self::ROUTE, 'not an integer'), $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure([
                'message',
                'additional_info' => [
                    'job_id'
                ]
            ])->assertJson([
                'message' => 'Job id must be an integer',
                'additional_info' => [
                    'job_id' => 'not an integer'
                ]
            ]);
    }

    public function testJobExistsValidation()
    {
        $payload = $this->generatePayload();
        $jobId = Job::max('id') + 1;
        $this
            ->actingAs($this->generateRecruiterUser())
            ->json('PUT', sprintf(self::ROUTE, $jobId), $payload)
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
        return $this->generatePayloadWithNonexistentCompany() + [
                'company_id' => Company::factory()->create()->id,
            ];
    }

    public function generatePayloadWithNonexistentCompany(): array
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'is_available' => true,
            'applications_amount' => $this->faker->numberBetween(5, 100),
            'salary' => $this->faker->numberBetween(0, 10000),
            'salary_time_unit' => $this->faker->randomElement(array_column(SalaryTimeUnitEnum::cases(), 'value')),
            'accept_application_until' => Carbon::now()->addMonth()->format('Y-m-d H:i:s'),
            'work_model' => $this->faker->randomElement(array_column(WorkModelEnum::cases(), 'value')),
            'employment_type' => $this->faker->randomElement(array_column(EmploymentTypeEnum::cases(), 'value')),
            'week_workload' => $this->faker->numberBetween(20, 40),
            'location' => $this->faker->address,
            'company_id' => Company::max('id') + 1
        ];
    }

    private function generateRecruiterUser(): User
    {
        return User::factory()->create([
            'type' => UserTypeEnum::Recruiter->value
        ]);
    }
}
