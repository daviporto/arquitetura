<?php

namespace Tests\Feature\User\AcademicRecord;

use App\Domain\User\UserTypeEnum;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AddAcademicRecordsToUserTest extends TestCase
{
    use DatabaseTransactions;

    const ROUTE = self::BASE_ROUTE . 'user/academic-records';

    public function testAddAcademicRecordsToUser()
    {
        /** @var User $user */
        $user = User::factory()->create([
            'type' => UserTypeEnum::Employee->value
        ]);

        $records = $this->generateRecordsPayload();

        $this
            ->actingAs($user)
            ->json('POST', self::ROUTE, ['academic_records' => $records])
            ->assertStatus(Response::HTTP_NO_CONTENT);

        foreach ($records as $record) {
            $this->assertTrue(
                $user->academicRecords()
                    ->where('institution', $record['institution'])
                    ->where('degree', $record['degree'])
                    ->where('field_of_study', $record['field_of_study'])
                    ->where('start_date', $record['start_date'])
                    ->where('description', $record['description'])
                    ->where('is_in_progress', $record['is_in_progress'])
                    ->exists()
            );
        }
    }

    private function generateRecordsPayload(): array
    {
        $faker = Factory::create();

        return [
            [
                'institution' => $faker->company,
                'degree' => $faker->word,
                'field_of_study' => $faker->word,
                'start_date' => Carbon::now()->format('Y-m-d'),
                'end_date' => Carbon::now()->addYear()->format('Y-m-d'),
                'description' => $faker->sentence,
                'is_in_progress' => false
            ],
            [
                'institution' => $faker->company,
                'degree' => $faker->word,
                'field_of_study' => $faker->word,
                'start_date' => Carbon::now()->format('Y-m-d'),
                'description' => $faker->sentence,
                'is_in_progress' => true
            ],
            [
                'institution' => $faker->company,
                'degree' => $faker->word,
                'field_of_study' => $faker->word,
                'start_date' => Carbon::now()->format('Y-m-d'),
                'end_date' => Carbon::now()->addYear()->format('Y-m-d'),
                'description' => $faker->sentence,
                'is_in_progress' => true
            ],
        ];
    }
}
