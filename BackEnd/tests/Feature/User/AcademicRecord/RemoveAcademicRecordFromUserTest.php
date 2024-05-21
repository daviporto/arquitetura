<?php

namespace Tests\Feature\User\AcademicRecord;

use App\Models\AcademicRecord;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RemoveAcademicRecordFromUserTest extends TestCase
{
    use DatabaseTransactions;

    const ROUTE = self::BASE_ROUTE . 'user/academic-records';

    public function testAddAcademicRecordsToUser()
    {
        $this->makeEmployee();

        $academicRecordIds = $this->createAcademicRecords();

        $this
            ->actingAs($this->employee)
            ->json('DELETE', self::ROUTE, ['academic_records_id' => $academicRecordIds])
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertSame(0, $this->employee->academicRecords()->count());
    }

    private function createAcademicRecords(): array
    {
        return AcademicRecord::factory()
            ->count(3)
            ->create(['user_id' => $this->employee->id])
            ->pluck('id')
            ->toArray();
    }
}
