<?php

namespace App\Domain\User;

use App\Domain\Competence\CompetenceDomainInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

interface UserDomainInterface
{
    function fromArray(array $user): self;

    function update(): self;

    function createUser(): self;

    function loadUser(int $userId): self;

    function toArray(): array;

    function exists(): bool;

    function users(): array;

    /** @param Collection<CompetenceDomainInterface> $competences */
    function attachCompetences(Collection $competences): self;

    function getName(): string;

    function setName(string $name): self;

    function getEmail(): string;

    function setEmail(string $email): self;

    function getPhone(): string;

    function setPhone(string $phone): self;

    function getType(): UserTypeEnum;

    function setType(UserTypeEnum $type): self;

    function getId(): ?int;

    function setId(?int $id): self;

    function getRepository(): UserRepositoryInterface;

    function removeCompetence(int $competenceId): self;

    function loadUserWithIncludes(int $userId, array $includes): array;

    function usersWithIncludes(array $includes): array;

    function createProfilePicture(UploadedFile $file, int $userId): void;

    function updateProfilePicture(UploadedFile $profilePicture, int $userId): string;

    function deleteProfilePicture(): void;
}
