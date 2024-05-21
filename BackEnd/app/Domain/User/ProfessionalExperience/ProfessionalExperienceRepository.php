<?php

namespace App\Domain\User\ProfessionalExperience;

use App\Domain\Abstract\AbstractRepository;
use App\Models\ProfessionalExperience;

class ProfessionalExperienceRepository extends AbstractRepository implements ProfessionalExperienceRepositoryInterface
{
    public function create(ProfessionalExperienceDomainInterface $current, int $userId): void
    {
        ProfessionalExperience::create(['user_id' => $userId] + $current->toArray());
    }

    public function exists(int $recordId): bool
    {
        return ProfessionalExperience::where('id', $recordId)->exists();
    }

    public function isOwner(int $experienceId, int $userId): bool
    {
        return ProfessionalExperience::where('id', $experienceId)
            ->where('user_id', $userId)
            ->exists();
    }

    public function delete(int $experienceId): void
    {
        ProfessionalExperience::where('id', $experienceId)->delete();
    }
}
