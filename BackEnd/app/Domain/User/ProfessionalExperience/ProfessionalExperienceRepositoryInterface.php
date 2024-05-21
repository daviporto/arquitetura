<?php

namespace App\Domain\User\ProfessionalExperience;

interface ProfessionalExperienceRepositoryInterface
{
    public function create(ProfessionalExperienceDomainInterface $current, int $userId): void;

    public function exists(int $recordId): bool;

    public function isOwner(int $experienceId, int $userId): bool;

    public function delete(int $experienceId): void;
}
