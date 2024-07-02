<?php

namespace App\Domain\Job;


use App\Domain\Competence\CompetencesIdFilterMustBePositiveIntegersException;
use App\Exceptions\Job\CompanyIdsFilterMustBePositiveIntegersException;
use App\Exceptions\Job\IdRequiredToUpdateException;
use App\Exceptions\Job\InvalidAcceptApplicationUntilDateFormatException;
use App\Exceptions\Job\JobAcceptApplicationsUntilPassedException;
use App\Exceptions\Job\JobApplicationsAmountSurpassedException;
use App\Exceptions\Job\JobNotFoundException;
use App\Exceptions\Job\OnlyOwnerCanUpdateJobException;
use App\Exceptions\Job\SalaryToMustBeBiggerThanFromException;
use App\Exceptions\Job\UnknownEmploymentTypesFilterException;
use App\Exceptions\Job\UnknownSalaryTimeUnitsFilterException;
use App\Exceptions\Job\UnknownWorkModelsFilterException;
use App\Exceptions\Job\UserIdsFilterMustBePositiveIntegersException;
use App\Exceptions\Job\WeekWorkloadMustBePositiveException;
use App\Exceptions\Job\WeekWorkloadToMustBeBiggerThanFromException;
use App\Http\Requests\Job\JobFilters;
use Carbon\Carbon;
use Illuminate\Support\Collection;

readonly class JobDomain implements JobDomainInterface
{
    private string $name;
    private string $description;
    private bool $isAvailable;
    private int $applicationsAmount;
    private ?int $id;
    private ?int $salary;
    private ?string $salaryTimeUnit;
    private ?Carbon $acceptApplicationUntil;
    private ?string $workModel;
    private ?string $employmentType;
    private ?int $weekWorkload;
    private ?string $location;
    private int $userId;
    private int $companyId;
    private ?Carbon $createdAt;
    private ?Carbon $updatedAt;

    public function __construct(private JobRepositoryInterface $jobRepository)
    {
    }

    public function save(): self
    {
        $attributes = $this->jobRepository->createJob($this);

        return (new self($this->jobRepository))->fromArray($attributes);
    }

    public function fromArray(array $job): self
    {
        $this->name = $job['name'];
        $this->description = $job['description'];
        $this->isAvailable = $job['is_available'];
        $this->applicationsAmount = $job['applications_amount'];
        $this->salary = $job['salary'] ?? null;
        $this->salaryTimeUnit = $job['salary_time_unit'] ?? null;
        $this->acceptApplicationUntil = isset($job['accept_application_until'])
            ? Carbon::createFromTimeString($job['accept_application_until']) : null;
        $this->workModel = $job['work_model'] ?? null;
        $this->employmentType = $job['employment_type'] ?? null;
        $this->weekWorkload = $job['week_workload'] ?? null;
        $this->location = $job['location'] ?? null;
        $this->companyId = $job['company_id'];
        $this->userId = $job['user_id'];

        isset($job['id']) && $this->id = $job['id'];

        return $this;
    }

    public function exists(int $id): bool
    {
        return $this->jobRepository->jobExists($id);
    }

    /**
     * @throws JobNotFoundException
     */
    public function assureExists(int $id): self
    {
        if (!$this->exists($id)) {
            throw new JobNotFoundException($id);
        }

        return $this;
    }

    /**
     * @throws IdRequiredToUpdateException
     * @throws OnlyOwnerCanUpdateJobException
     */
    public function update(): self
    {
        if ($this->id === null) {
            throw new IdRequiredToUpdateException();
        }

        if ($this->jobRepository->getJobOwner($this->id) != $this->userId) {
            throw new OnlyOwnerCanUpdateJobException();
        }

        $this->jobRepository->update($this);

        return $this;
    }

    /**
     * @throws IdRequiredToUpdateException
     * @throws OnlyOwnerCanUpdateJobException
     */
    public function delete(): void
    {
        if ($this->id === null) {
            throw new IdRequiredToUpdateException();
        }

        if ($this->jobRepository->getJobOwner($this->id) != $this->userId) {
            throw new OnlyOwnerCanUpdateJobException();
        }

        $this->jobRepository->delete($this->id);
    }

    /**
     * @throws JobApplicationsAmountSurpassedException
     * @throws JobAcceptApplicationsUntilPassedException
     */
    public function acceptApplication(): self
    {

        if ($this->getAcceptApplicationUntil()->isPast()) {
            $this->isAvailable = false;

            $this->jobRepository->setNotAvailable($this->id);

            throw new JobAcceptApplicationsUntilPassedException(
                $this->getAcceptApplicationUntil()->toDateTimeString()
            );
        }

        if ($this->jobRepository->getApplicationAmount($this->id) >= $this->getApplicationsAmount()) {
            $this->isAvailable = false;
            $this->jobRepository->setNotAvailable($this->id);

            throw new JobApplicationsAmountSurpassedException();
        }

        return $this;
    }

    public function getAcceptApplicationUntil(): ?Carbon
    {
        return $this->acceptApplicationUntil;
    }

    public function getApplicationsAmount(): int
    {
        return $this->applicationsAmount;
    }

    /**
     * @throws SalaryToMustBeBiggerThanFromException
     * @throws CompetencesIdFilterMustBePositiveIntegersException
     * @throws UnknownWorkModelsFilterException
     * @throws CompanyIdsFilterMustBePositiveIntegersException
     * @throws InvalidAcceptApplicationUntilDateFormatException
     * @throws WeekWorkloadToMustBeBiggerThanFromException
     * @throws UnknownEmploymentTypesFilterException
     * @throws WeekWorkloadMustBePositiveException
     * @throws UnknownSalaryTimeUnitsFilterException
     * @throws UserIdsFilterMustBePositiveIntegersException
     */
    public function jobsWithIncludes(array $filers, array $includes): array
    {
        if (!empty($filers)) {
            $filers = (new JobFilters())->fromArray($filers);

            return $this->jobRepository->getJobsWithFilters($filers, $includes);
        }

        return $this->jobRepository->getJobs($includes);
    }

    public function getJobWithIncludes(array $includes): array
    {
        return $this->jobRepository->getJobWithIncludes($this->id, $includes);
    }

    public function attachCompetences(Collection $competences): self
    {
        $this->jobRepository->attachCompetences($this->id, $competences);

        return $this;
    }

    public function load(): self
    {
        $job = $this->jobRepository->getJob($this->id);

        $this->name = $job['name'];
        $this->description = $job['description'];
        $this->isAvailable = $job['is_available'];
        $this->applicationsAmount = $job['applications_amount'];
        $this->salary = $job['salary'];
        $this->salaryTimeUnit = $job['salary_time_unit'];
        $this->acceptApplicationUntil = $job['accept_application_until']
            ? Carbon::createFromTimeString($job['accept_application_until'])
            : null;
        $this->workModel = $job['work_model'];
        $this->employmentType = $job['employment_type'];
        $this->weekWorkload = $job['week_workload'];
        $this->location = $job['location'];
        $this->companyId = $job['company_id'];
        $this->createdAt = $job['created_at']
            ? Carbon::createFromTimeString($job['created_at'])
            : null;
        $this->updatedAt = $job['updated_at']
            ? Carbon::createFromTimeString($job['updated_at'])
            : null;

        return $this;
    }

    public function toArray(): array
    {
        $arr = [
            'name' => $this->name,
            'description' => $this->description,
            'is_available' => $this->isAvailable,
            'applications_amount' => $this->applicationsAmount,
            'salary' => $this->salary,
            'salary_time_unit' => $this->salaryTimeUnit,
            'accept_application_until' => $this->acceptApplicationUntil?->format('Y-m-d H:i:s'),
            'work_model' => $this->workModel,
            'employment_type' => $this->employmentType,
            'week_workload' => $this->weekWorkload,
            'location' => $this->location,
            'user_id' => $this->userId,
            'company_id' => $this->companyId,
        ];

        !empty($this->id) && $arr['id'] = $this->id;
        !empty($this->createdAt) && $arr['created_at'] = $this->createdAt->format('Y-m-d H:i:s');
        !empty($this->updatedAt) && $arr['updated_at'] = $this->updatedAt->format('Y-m-d H:i:s');

        return $arr;
    }

    public function deleteCompetences(): self
    {
        $this->jobRepository->deleteCompetences($this->id);

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getCompanyId(): int
    {
        return $this->companyId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isAvailable(): bool
    {
        return $this->isAvailable;
    }

    public function getSalary(): ?int
    {
        return $this->salary;
    }

    public function getSalaryTimeUnit(): ?string
    {
        return $this->salaryTimeUnit;
    }

    public function getWorkModel(): ?string
    {
        return $this->workModel;
    }

    public function getEmploymentType(): ?string
    {
        return $this->employmentType;
    }

    public function getWeekWorkload(): ?int
    {
        return $this->weekWorkload;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }
}
