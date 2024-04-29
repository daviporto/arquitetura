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
        $this->makeUser();

        $competenceIds = $this->attachCompetencesToUser();

        $this
            ->actingAs($this->user)
            ->json('DELETE', self::ROUTE, ['competencesId' => $competenceIds])
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertSame(0, $this->user->competences()->count());
    }

    private function attachCompetencesToUser(): array
    {
        $competences = Competence::factory(3)->create();

        $this
            ->user
            ->competences()
            ->attach($competences->pluck('id')->toArray());

        return $competences
            ->pluck('id')
            ->toArray();
    }
}
