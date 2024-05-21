<?php

namespace Tests\Feature\User\ProfessionalExperience;

use App\Models\ProfessionalExperience;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RemoveProfessionalExperienceFromUserTest extends TestCase
{
    use DatabaseTransactions;

    const ROUTE = self::BASE_ROUTE . 'user/professional-experiences';

    public function testAddAcademicRecordsToUser()
    {
        $this->makeEmployee();

        $academicRecordIds = $this->createProfessionalExperiences();

        $this
            ->actingAs($this->employee)
            ->json('DELETE', self::ROUTE, ['professional_experiences_id' => $academicRecordIds])
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertSame(0, $this->employee->academicRecords()->count());
    }

    private function createProfessionalExperiences(): array
    {
        return ProfessionalExperience::factory()
            ->count(3)
            ->create(['user_id' => $this->employee->id])
            ->pluck('id')
            ->toArray();
    }
}
