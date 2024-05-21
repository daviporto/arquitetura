<?php

namespace App\Domain\User\ProfessionalExperience;

use App\Domain\Job\Enum\WorkModelEnum;
use App\Exceptions\User\ProfessionalExperience\CurrentExperienceEndDateMustBeInTheFutureException;
use App\Exceptions\User\ProfessionalExperience\EndDateMustBeAfterStartDateException;
use App\Exceptions\User\ProfessionalExperience\MustHaveEndDateWhenFinishedExperienceException;
use Carbon\Carbon;

readonly class ProfessionalExperienceDomain implements ProfessionalExperienceDomainInterface
{
    private string $companyName;
    private string $position;
    private ?string $description;
    private ?string $location;
    private Carbon $startDate;
    private ?Carbon $endDate;
    private bool $isCurrent;
    private ?WorkModelEnum $workModel;
    private ?EmploymentTypeEnum $employmentType;
    private ?int $companyId;

    public function __construct(private ProfessionalExperienceRepositoryInterface $repository)
    {
    }

    /**
     * @throws MustHaveEndDateWhenFinishedExperienceException
     * @throws CurrentExperienceEndDateMustBeInTheFutureException
     * @throws EndDateMustBeAfterStartDateException
     */
    public function createMany(array $experiences, int $userId): void
    {
        foreach ($experiences as $experience) {
            $current = (new self($this->repository))->fromArray($experience);

            $this->repository->create($current, $userId);
        }
    }

    /**
     * @throws MustHaveEndDateWhenFinishedExperienceException
     * @throws CurrentExperienceEndDateMustBeInTheFutureException
     * @throws EndDateMustBeAfterStartDateException
     */
    public function fromArray(array $experience): self
    {
        $this->setCompanyName($experience['company_name']);
        $this->setPosition($experience['position']);
        $this->setDescription($experience['description'] ?? null);
        $this->setLocation($experience['location'] ?? null);
        $this->setStartDate(Carbon::parse($experience['start_date']));
        $this->setIsCurrent($experience['is_current'] ?? false);
        $this->setEndDate($experience['end_date'] ?? null);
        $this->setWorkModel($experience['work_model'] ?? null);
        $this->setEmploymentType($experience['employment_type'] ?? null);
        $this->setCompanyId($experience['company_id'] ?? null);

        return $this;
    }

    public function toArray(): array
    {
        return [
            'company_name' => $this->getCompanyName(),
            'position' => $this->getPosition(),
            'description' => $this->getDescription(),
            'location' => $this->getLocation(),
            'start_date' => $this->getStartDate()->toDateString(),
            'end_date' => $this->getEndDate()?->toDateString(),
            'is_current' => $this->isCurrent(),
            'work_model' => $this->getWorkModel()?->value,
            'employment_type' => $this->getEmploymentType()?->value,
            'company_id' => $this->getCompanyId(),
        ];
    }

    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    public function setCompanyName(string $companyName): ProfessionalExperienceDomain
    {
        $this->companyName = $companyName;
        return $this;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function setPosition(string $position): ProfessionalExperienceDomain
    {
        $this->position = $position;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): ProfessionalExperienceDomain
    {
        $this->description = $description;
        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): ProfessionalExperienceDomain
    {
        $this->location = $location;
        return $this;
    }

    public function getStartDate(): Carbon
    {
        return $this->startDate;
    }

    public function setStartDate(Carbon $startDate): ProfessionalExperienceDomain
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?Carbon
    {
        return $this->endDate;
    }

    /**
     * @throws MustHaveEndDateWhenFinishedExperienceException
     * @throws EndDateMustBeAfterStartDateException
     * @throws CurrentExperienceEndDateMustBeInTheFutureException
     */
    public function setEndDate(string|Carbon|null $date): self
    {
        if (empty($date)) {
            if (!$this->isCurrent) {
                throw new MustHaveEndDateWhenFinishedExperienceException();
            }

            $this->endDate = null;
        } else if ($date instanceof Carbon) {
            $this->endDate = $date;
        } else {
            $this->endDate = Carbon::parse($date);
        }

        if (isset($this->startDate) && $this->startDate->isAfter($this->endDate)) {
            throw new EndDateMustBeAfterStartDateException($this->startDate, $this->endDate);
        }

        if ($this->isCurrent && isset($this->endDate) && $this->endDate->isPast()) {
            throw new CurrentExperienceEndDateMustBeInTheFutureException();
        }

        return $this;
    }

    public function isCurrent(): bool
    {
        return $this->isCurrent;
    }

    public function setIsCurrent(bool $isCurrent): ProfessionalExperienceDomain
    {
        $this->isCurrent = $isCurrent;
        return $this;
    }

    public function getWorkModel(): ?WorkModelEnum
    {
        return $this->workModel;
    }

    public function setWorkModel(WorkModelEnum|string|null $workModel): ProfessionalExperienceDomain
    {
        if (!isset($workModel)) {
            $this->workModel = null;
        } else if ($workModel instanceof WorkModelEnum) {
            $this->workModel = $workModel;
        } else {
            $this->workModel = WorkModelEnum::from($workModel);
        }

        return $this;
    }

    public function getEmploymentType(): ?EmploymentTypeEnum
    {
        return $this->employmentType;
    }

    public function setEmploymentType(EmploymentTypeEnum|string|null $employmentType): ProfessionalExperienceDomain
    {
        if (!isset($employmentType)) {
            $this->employmentType = null;
        } else if ($employmentType instanceof EmploymentTypeEnum) {
            $this->employmentType = $employmentType;
        } else {
            $this->employmentType = EmploymentTypeEnum::from($employmentType);
        }

        return $this;
    }

    public function exists(int $recordId): bool
    {
        return $this->repository->exists($recordId);
    }

    public function isOwner(mixed $experience, int $userId): bool
    {
        return $this->repository->isOwner($experience, $userId);
    }

    public function delete(int $experienceId): void
    {
        $this->repository->delete($experienceId);
    }

    public function getCompanyId(): ?int
    {
        return $this->companyId;
    }

    public function setCompanyId(?int $companyId): self
    {
        $this->companyId = $companyId;

        return $this;
    }
}
