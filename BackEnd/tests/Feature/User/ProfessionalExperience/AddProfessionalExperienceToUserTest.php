<?php

namespace Tests\Feature\User\ProfessionalExperience;

use App\Domain\Job\Enum\EmploymentTypeEnum;
use App\Domain\Job\Enum\WorkModelEnum;
use App\Domain\User\UserTypeEnum;
use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AddProfessionalExperienceToUserTest extends TestCase
{
    use DatabaseTransactions;

    const ROUTE = self::BASE_ROUTE . 'user/professional-experiences';

    public function testAddProfessionalExperiences()
    {
        /** @var User $user */
        $user = User::factory()->create([
            'type' => UserTypeEnum::Employee->value
        ]);

        $experiences = $this->generateProfessionalExperiencesPayload();

        $this
            ->actingAs($user)
            ->json('POST', self::ROUTE, ['professional_experiences' => $experiences])
            ->assertStatus(Response::HTTP_NO_CONTENT);

        foreach ($experiences as $experience) {
            $this->assertDatabaseHas('professional_experiences', [
                'user_id' => $user->id,
                'company_name' => $experience['company_name'],
                'position' => $experience['position'],
                'start_date' => Carbon::parse($experience['start_date'])->format('Y-m-d'),
                'description' => $experience['description'],
                'is_current' => $experience['is_current'],
                'end_date' => $experience['end_date'] ?? null,
                'work_model' => $experience['work_model'] ?? null,
                'employment_type' => $experience['employment_type'] ?? null
            ]);
        }
    }

    private function generateProfessionalExperiencesPayload(): array
    {
        $faker = Factory::create();

        return [
            [
                'company_name' => $faker->company,
                'position' => $faker->word,
                'start_date' => Carbon::now()->format('Y-m-d'),
                'description' => $faker->sentence,
                'is_current' => true
            ],
            [
                'company_name' => $faker->company,
                'position' => $faker->word,
                'start_date' => Carbon::now()->subYear()->format('Y-m-d'),
                'end_date' => Carbon::now()->format('Y-m-d'),
                'description' => $faker->sentence,
                'is_current' => false,
                'work_model' => WorkModelEnum::Hybrid->value,
                'employment_type' => EmploymentTypeEnum::FullTime->value
            ],
            [
                'company_name' => $faker->company,
                'position' => $faker->word,
                'start_date' => Carbon::now()->subYears(2)->format('Y-m-d'),
                'end_date' => Carbon::now()->subYear()->format('Y-m-d'),
                'description' => $faker->sentence,
                'is_current' => false,
                'work_model' => WorkModelEnum::HomeOffice->value,
                'employment_type' => EmploymentTypeEnum::PartTime->value
            ]
        ];
    }

    public function testCreateWithCompany()
    {
        /** @var User $user */
        $user = User::factory()->create([
            'type' => UserTypeEnum::Employee->value
        ]);

        $experiences = $this->generateProfessionalExperiencesPayload();
        $experience = reset($experiences);

        /** @var Company $company */
        $company = Company::factory()->create();
        $experience['company_id'] = $company->id;

        $this
            ->actingAs($user)
            ->json('POST', self::ROUTE, ['professional_experiences' => [$experience]])
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseHas('professional_experiences', [
            'user_id' => $user->id,
            'company_name' => $experience['company_name'],
            'company_id' => $company->id,
            'position' => $experience['position'],
            'start_date' => Carbon::parse($experience['start_date'])->format('Y-m-d'),
            'description' => $experience['description'],
            'is_current' => $experience['is_current'],
            'end_date' => $experience['end_date'] ?? null,
            'work_model' => $experience['work_model'] ?? null,
            'employment_type' => $experience['employment_type'] ?? null
        ]);
    }
}
