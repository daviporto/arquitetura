<?php

namespace Tests\Feature\User\Competence;

use App\Models\Competence;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RemoveCompetenceToUserTest extends TestCase
{
    use DatabaseTransactions;

    const ROUTE = self::BASE_ROUTE . 'user/competence';

    public function testAddCompetencesToUser()
    {
        $this->makeEmployee();

        $competenceIds = $this->attachCompetencesToUser();

        $this
            ->actingAs($this->employee)
            ->json('DELETE', self::ROUTE, ['competences_id' => $competenceIds])
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertSame(0, $this->employee->competences()->count());
    }

    private function attachCompetencesToUser(): array
    {
        $competences = Competence::factory(3)->create();

        $this
            ->employee
            ->competences()
            ->attach($competences->pluck('id')->toArray());

        return $competences
            ->pluck('id')
            ->toArray();
    }
}
