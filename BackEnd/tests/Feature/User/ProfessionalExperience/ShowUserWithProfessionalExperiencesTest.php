<?php

namespace Tests\Feature\User\ProfessionalExperience;

use App\Domain\User\UserIncludesEnum;
use App\Models\ProfessionalExperience;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ShowUserWithProfessionalExperiencesTest extends TestCase
{
    use DatabaseTransactions;

    const ROUTE = self::BASE_ROUTE . 'user/show/%s';

    public function testShowUserSuccess()
    {
        $this->makeEmployee();
        $this->makeProfessionalExperiences();

        $this
            ->actingAs(User::factory()->create())
            ->json(
                'GET',
                sprintf(self::ROUTE, $this->employee->id),
                [
                    'includes' => [UserIncludesEnum::ProfessionalExperiences->value]
                ]
            )
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(3, 'data.professional_experiences')
            ->assertJsonStructure([
                'data' => [
                    'name',
                    'email',
                    'phone',
                    'id',
                    'type',
                    'professional_experiences' => [
                        '*' => [
                            'id',
                            'company_name',
                            'position',
                            'company_id',
                            'start_date',
                            'end_date',
                            'is_current',
                            'location',
                            'work_model',
                            'employment_type',
                            'description',
                            'created_at',
                            'updated_at',
                        ]
                    ]
                ]
            ]);
    }

    private function makeProfessionalExperiences(): void
    {
        ProfessionalExperience::factory()
            ->count(3)
            ->create(['user_id' => $this->employee->id]);
    }
}
