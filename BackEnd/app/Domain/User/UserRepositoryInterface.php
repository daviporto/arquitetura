<?php

namespace App\Domain\User;

use App\Domain\Competence\CompetenceDomainInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function createUser(array $user): array;

    public function forgotPassword(UserDomain $user): void;

    public function getUsers(): array;

    public function exists(int $id): bool;

    public function getUser(int $id): array;

    public function isEmailAvailableToUpdate(UserDomainInterface $user): bool;

    public function isPhoneAvailable(UserDomainInterface $user): bool;

    public function update(UserDomainInterface $user): void;

    /** @param Collection<CompetenceDomainInterface> $competences */
    public function attachCompetences(int $id, Collection $competences);

    public function removeCompetence(int $id, int $competenceId): void;

    public function userHasCompetence(?int $id, int $competenceId): bool;

    public function getUserWithIncludes(int $userId, array $includes): array;

    public function getUsersWithIncludes(array $includes): array;

    public function createProfilePicture(UploadedFile $file, int $userId): string;

    public function deleteProfilePicture(string $profilePicturePath, int $userId): void;
}
