<?php

namespace App\Http\Requests\Job;

use App\Domain\Competence\CompetencesIdFilterMustBePositiveIntegersException;
use App\Domain\Job\Enum\EmploymentTypeEnum;
use App\Domain\Job\Enum\SalaryTimeUnitEnum;
use App\Domain\Job\Enum\WorkModelEnum;
use App\Exceptions\Job\CompanyIdsFilterMustBePositiveIntegersException;
use App\Exceptions\Job\InvalidAcceptApplicationUntilDateFormatException;
use App\Exceptions\Job\SalaryToMustBeBiggerThanFromException;
use App\Exceptions\Job\UnknownEmploymentTypesFilterException;
use App\Exceptions\Job\UnknownSalaryTimeUnitsFilterException;
use App\Exceptions\Job\UnknownWorkModelsFilterException;
use App\Exceptions\Job\UserIdsFilterMustBePositiveIntegersException;
use App\Exceptions\Job\WeekWorkloadMustBePositiveException;
use App\Exceptions\Job\WeekWorkloadToMustBeBiggerThanFromException;
use Carbon\Carbon;

class JobFilters implements JobFiltersInterface
{
    const DATE_TIME_FORMAT = 'Y-m-d H:i:s';
    private ?string $name;
    private ?string $description;
    private ?bool $isAvailable;
    private ?int $salaryFrom;
    private ?int $salaryTo;
    private ?array $salaryTimeUnits;
    private ?Carbon $acceptApplicationUntil;
    private ?array $workModels;
    private ?array $employmentTypes;
    private ?int $weekWorkloadFrom;
    private ?int $weekWorkloadTo;
    private ?string $location;
    private ?array $companyIds;
    private ?array $userIds;
    private ?array $competencesId;

    /**
     * @param array{
     *     name?: string,
     *     description?: string,
     *     is_available?: bool,
     *     salary_from?: int,
     *     salary_to?: int,
     *     salary_time_units?: array,
     *     accept_application_until?: string|Carbon,
     *     work_models?: array,
     *     employment_types?: array,
     *     week_workload_from?: int,
     *     week_workload_to?: int,
     *     location?: string,
     *     company_ids?: array,
     *     user_ids?: array,
     *     competence_ids?: array
     * } $filters
     * @throws SalaryToMustBeBiggerThanFromException
     * @throws UnknownWorkModelsFilterException
     * @throws CompetencesIdFilterMustBePositiveIntegersException
     * @throws CompanyIdsFilterMustBePositiveIntegersException
     * @throws InvalidAcceptApplicationUntilDateFormatException
     * @throws WeekWorkloadToMustBeBiggerThanFromException
     * @throws UnknownEmploymentTypesFilterException
     * @throws WeekWorkloadMustBePositiveException
     * @throws UnknownSalaryTimeUnitsFilterException
     * @throws UserIdsFilterMustBePositiveIntegersException
     */
    public function fromArray(array $filters): self
    {
        $this->setName($filters['name'] ?? null);
        $this->setDescription($filters['description'] ?? null);
        $this->setIsAvailable($filters['is_available'] ?? null);
        $this->setSalaryFrom($filters['salary_from'] ?? null);
        $this->setSalaryTo($filters['salary_to'] ?? null);
        $this->setSalaryTimeUnits($filters['salary_time_units'] ?? null);
        $this->setAcceptApplicationUntil($filters['accept_application_until'] ?? null);
        $this->setWorkModels($filters['work_models'] ?? null);
        $this->setEmploymentTypes($filters['employment_types'] ?? null);
        $this->setWeekWorkloadFrom($filters['week_workload_from'] ?? null);
        $this->setWeekWorkloadTo($filters['week_workload_to'] ?? null);
        $this->setLocation($filters['location'] ?? null);
        $this->setCompanyIds($filters['company_ids'] ?? null);
        $this->setUserIds($filters['user_ids'] ?? null);
        $this->setCompetencesId($filters['competence_ids'] ?? null);

        return $this;
    }

    public function setName(?string $name): JobFilters
    {
        $this->name = $name;

        return $this;
    }

    public function setDescription(?string $description): JobFilters
    {
        $this->description = $description;

        return $this;
    }

    public function setIsAvailable(?bool $isAvailable): JobFilters
    {
        $this->isAvailable = $isAvailable;

        return $this;
    }

    public function setSalaryFrom(?int $salaryFrom): JobFilters
    {
        $this->salaryFrom = $salaryFrom;

        return $this;
    }

    /**
     * @throws SalaryToMustBeBiggerThanFromException
     */
    public function setSalaryTo(?int $salaryTo): JobFilters
    {
        if (isset($this->salaryFrom) && isset($salaryTo) && $this->getSalaryFrom() > $salaryTo) {
            throw new SalaryToMustBeBiggerThanFromException($this->getSalaryFrom(), $salaryTo);
        }

        $this->salaryTo = $salaryTo;

        return $this;
    }

    public function getSalaryFrom(): ?int
    {
        return $this->salaryFrom;
    }

    /**
     * @throws UnknownSalaryTimeUnitsFilterException
     */
    public function setSalaryTimeUnits(?array $salaryTimeUnits): JobFilters
    {
        if (empty($salaryTimeUnits)) {
            $this->salaryTimeUnits = null;

            return $this;
        }

        $unknownUnits = array_diff($salaryTimeUnits, SalaryTimeUnitEnum::values());

        if (!empty($unknownUnits)) {
            throw new UnknownSalaryTimeUnitsFilterException($unknownUnits);
        }

        $this->salaryTimeUnits = $salaryTimeUnits;

        return $this;
    }

    /**
     * @throws InvalidAcceptApplicationUntilDateFormatException
     */
    public function setAcceptApplicationUntil(Carbon|string|null $acceptApplicationUntil): JobFilters
    {
        if (is_string($acceptApplicationUntil)) {
            if (Carbon::hasFormat($acceptApplicationUntil, self::DATE_TIME_FORMAT)) {
                $acceptApplicationUntil = Carbon::createFromFormat(self::DATE_TIME_FORMAT, $acceptApplicationUntil);
            } else {
                throw new InvalidAcceptApplicationUntilDateFormatException(
                    $acceptApplicationUntil,
                    self::DATE_TIME_FORMAT
                );
            }
        }
        $this->acceptApplicationUntil = $acceptApplicationUntil;

        return $this;
    }

    /**
     * @throws UnknownWorkModelsFilterException
     */
    public function setWorkModels(?array $workModels): JobFilters
    {
        if (empty($workModels)) {
            $this->workModels = null;

            return $this;
        }

        $unknownModels = array_diff($workModels, WorkModelEnum::values());

        if (!empty($unknownModels)) {
            throw new UnknownWorkModelsFilterException($unknownModels);
        }

        $this->workModels = $workModels;

        return $this;
    }

    /**
     * @throws UnknownEmploymentTypesFilterException
     */
    public function setEmploymentTypes(?array $employmentTypes): JobFilters
    {
        if (empty($employmentTypes)) {
            $this->employmentTypes = null;

            return $this;
        }

        $unknownEmploymentTypes = array_diff($employmentTypes, EmploymentTypeEnum::values());

        if (!empty($unknownEmploymentTypes)) {
            throw new UnknownEmploymentTypesFilterException($unknownEmploymentTypes);
        }

        $this->employmentTypes = $employmentTypes;

        return $this;
    }

    /**
     * @throws WeekWorkloadMustBePositiveException
     */
    public function setWeekWorkloadFrom(?int $weekWorkloadFrom): JobFilters
    {
        if (isset($weekWorkloadFrom) && $weekWorkloadFrom < 1) {
            throw new WeekWorkloadMustBePositiveException($weekWorkloadFrom);
        }

        $this->weekWorkloadFrom = $weekWorkloadFrom;

        return $this;
    }

    /**
     * @throws WeekWorkloadMustBePositiveException
     * @throws WeekWorkloadToMustBeBiggerThanFromException
     */
    public function setWeekWorkloadTo(?int $weekWorkloadTo): JobFilters
    {
        if (isset($weekWorkloadTo) && $weekWorkloadTo < 1) {
            throw new WeekWorkloadMustBePositiveException($weekWorkloadTo);
        }

        if (isset($this->weekWorkloadFrom) && isset($weekWorkloadTo) && $this->weekWorkloadFrom > $weekWorkloadTo) {
            throw new WeekWorkloadToMustBeBiggerThanFromException($this->getWeekWorkloadFrom(), $weekWorkloadTo);
        }

        $this->weekWorkloadTo = $weekWorkloadTo;

        return $this;
    }

    public function getWeekWorkloadFrom(): ?int
    {
        return $this->weekWorkloadFrom;
    }

    public function setLocation(?string $location): JobFilters
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @throws CompanyIdsFilterMustBePositiveIntegersException
     */
    public function setCompanyIds(?array $companyIds): JobFilters
    {
        if (!empty($companyIds)) {
            $nonPositiveIntegers = array_filter(
                $companyIds,
                fn($companyId) => !is_int($companyId) || $companyId <= 0
            );

            if (!empty($nonPositiveIntegers)) {
                throw new CompanyIdsFilterMustBePositiveIntegersException();
            }
        }

        $this->companyIds = $companyIds;

        return $this;
    }

    /**
     * @throws CompetencesIdFilterMustBePositiveIntegersException
     */
    public function setCompetencesId(?array $competencesId): JobFilters
    {
        if (!empty($competencesId)) {
            $nonPositiveIntegers = array_filter(
                $competencesId,
                fn($competenceId) => !is_int($competenceId) || $competenceId <= 0
            );

            if (!empty($nonPositiveIntegers)) {
                throw new CompetencesIdFilterMustBePositiveIntegersException($nonPositiveIntegers);
            }
        }

        $this->competencesId = $competencesId;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'isAvailable' => $this->getIsAvailable(),
            'salaryFrom' => $this->getSalaryFrom(),
            'salaryTo' => $this->getSalaryTo(),
            'salary_time_units' => $this->getSalaryTimeUnits(),
            'accept_application_until' => $this->getAcceptApplicationUntil()?->format(self::DATE_TIME_FORMAT),
            'work_models' => $this->getWorkModels(),
            'employment_types' => $this->getEmploymentTypes(),
            'week_workload_from' => $this->getWeekWorkloadFrom(),
            'week_workload_to' => $this->getWeekWorkloadTo(),
            'location' => $this->getLocation(),
            'company_ids' => $this->getCompanyIds(),
            'competence_ids' => $this->getCompetencesId(),
            'user_ids' => $this->getUserIds(),
        ];
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getIsAvailable(): ?bool
    {
        return $this->isAvailable;
    }

    public function getSalaryTo(): ?int
    {
        return $this->salaryTo;
    }

    public function getSalaryTimeUnits(): ?array
    {
        return $this->salaryTimeUnits;
    }

    public function getAcceptApplicationUntil(): ?Carbon
    {
        return $this->acceptApplicationUntil;
    }

    public function getWorkModels(): ?array
    {
        return $this->workModels;
    }

    public function getEmploymentTypes(): ?array
    {
        return $this->employmentTypes;
    }

    public function getWeekWorkloadTo(): ?int
    {
        return $this->weekWorkloadTo;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function getCompanyIds(): ?array
    {
        return $this->companyIds;
    }

    public function getCompetencesId(): ?array
    {
        return $this->competencesId;
    }

    public function getUserIds(): ?array
    {
        return $this->userIds;
    }

    /**
     * @throws UserIdsFilterMustBePositiveIntegersException
     */
    public function setUserIds(?array $userIds): JobFilters
    {
        if (!empty($userIds)) {
            $nonPositiveIntegers = array_filter(
                $userIds,
                fn($userId) => !is_int($userId) || $userId <= 0
            );

            if (!empty($nonPositiveIntegers)) {
                throw new UserIdsFilterMustBePositiveIntegersException($userIds);
            }
        }

        $this->userIds = $userIds;

        return $this;
    }
}
