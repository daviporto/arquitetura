<?php

namespace App\Domain\User\ProfessionalExperience;

use App\Domain\Job\Enum\WorkModelEnum;
use Carbon\Carbon;

interface ProfessionalExperienceDomainInterface
{
    public function __construct(ProfessionalExperienceRepositoryInterface $repository);

    public function createMany(array $experiences, int $userId): void;

    public function fromArray(array $experience): self;

    public function toArray(): array;

    public function getCompanyName(): string;

    public function getPosition(): string;

    public function getDescription(): ?string;

    public function getLocation(): ?string;

    public function getStartDate(): Carbon;

    public function getEndDate(): ?Carbon;

    public function isCurrent(): bool;

    public function getWorkModel(): ?WorkModelEnum;

    public function getEmploymentType(): ?EmploymentTypeEnum;

    public function setCompanyName(string $companyName): ProfessionalExperienceDomainInterface;

    public function setPosition(string $position): ProfessionalExperienceDomainInterface;

    public function setDescription(?string $description): ProfessionalExperienceDomainInterface;

    public function setLocation(?string $location): ProfessionalExperienceDomainInterface;

    public function setStartDate(Carbon $startDate): ProfessionalExperienceDomainInterface;

    public function setEndDate(string|Carbon|null $date): ProfessionalExperienceDomainInterface;

    public function setIsCurrent(bool $isCurrent): ProfessionalExperienceDomainInterface;

    public function setWorkModel(WorkModelEnum|string|null $workModel): ProfessionalExperienceDomain;

    public function setEmploymentType(EmploymentTypeEnum|string|null $employmentType): ProfessionalExperienceDomain;

    public function exists(int $recordId): bool;

    public function isOwner(mixed $experience, int $userId): bool;

    public function delete(int $experienceId): void;
}
