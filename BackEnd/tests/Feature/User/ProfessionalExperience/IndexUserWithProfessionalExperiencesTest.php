<?php

namespace Tests\Feature\User\ProfessionalExperience;

use App\Domain\User\UserIncludesEnum;
use App\Models\ProfessionalExperience;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class IndexUserWithProfessionalExperiencesTest extends TestCase
{
    use DatabaseTransactions;

    const ROUTE = self::BASE_ROUTE . 'user';

    public function testShowUserSuccess()
    {
        $users = User::factory()->count(3)->create();

        $users->each(function ($user) {
            $this->makeProfessionalExperiences($user);
        });

        $this
            ->actingAs(User::factory()->create())
            ->json(
                'GET',
                sprintf(self::ROUTE, $users->first()->id),
                [
                    'includes' => [UserIncludesEnum::ProfessionalExperiences->value]
                ]
            )
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
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
                ]
            ]);
    }

    private function makeProfessionalExperiences(User $user): void
    {
        ProfessionalExperience::factory()
            ->count(3)
            ->create(['user_id' => $user->id]);
    }
}
