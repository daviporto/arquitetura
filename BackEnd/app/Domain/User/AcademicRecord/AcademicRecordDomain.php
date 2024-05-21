<?php

namespace App\Domain\User\AcademicRecord;

use App\Exceptions\User\AcademicRecord\EndDateMustBeAfterStartDateException;
use App\Exceptions\User\AcademicRecord\MustHaveEndDateWhenFinishedException;
use Carbon\Carbon;

readonly class AcademicRecordDomain implements AcademicRecordDomainInterface
{
    private ?int $id;
    private string $institution;
    private string $degree;
    private string $fieldOfStudy;
    private Carbon $startDate;
    private ?Carbon $endDate;
    private bool $isOnProgress;
    private ?string $description;

    public function __construct(private AcademicRecordRepositoryInterface $repository)
    {
    }

    /**
     * @throws EndDateMustBeAfterStartDateException
     * @throws MustHaveEndDateWhenFinishedException
     */
    public function createMany(array $records, int $userId): void
    {
        foreach ($records as $record) {
            $current = (new self($this->repository))->fromArray($record);

            $this->repository->create($current, $userId);
        }
    }

    /**
     * @throws MustHaveEndDateWhenFinishedException
     * @throws EndDateMustBeAfterStartDateException
     */
    public function fromArray(array $record): self
    {
        $this->institution = $record['institution'];
        $this->degree = $record['degree'];
        $this->fieldOfStudy = $record['field_of_study'];
        $this->startDate = Carbon::parse($record['start_date']);
        $this->description = $record['description'] ?? null;
        $this->isOnProgress = $record['is_in_progress'] ?? false;

        isset($record['end_date']) && $this->setEndDate($record['end_date']);

        return $this;
    }


    public function toArray(): array
    {
        return [
            'institution' => $this->getInstitution(),
            'degree' => $this->getDegree(),
            'field_of_study' => $this->getFieldOfStudy(),
            'start_date' => $this->getStartDate(),
            'end_date' => $this->getEndDate(),
            'is_in_progress' => $this->isOnProgress(),
            'description' => $this->getDescription(),
        ];
    }

    public function getInstitution(): string
    {
        return $this->institution;
    }

    public function setInstitution(string $institution): AcademicRecordDomain
    {
        $this->institution = $institution;

        return $this;
    }

    public function getDegree(): string
    {
        return $this->degree;
    }

    public function setDegree(string $degree): AcademicRecordDomain
    {
        $this->degree = $degree;

        return $this;
    }

    public function getFieldOfStudy(): string
    {
        return $this->fieldOfStudy;
    }

    public function setFieldOfStudy(string $fieldOfStudy): AcademicRecordDomain
    {
        $this->fieldOfStudy = $fieldOfStudy;

        return $this;
    }

    public function getStartDate(): string
    {
        return $this->startDate->format('Y-m-d');
    }

    public function setStartDate(Carbon $startDate): AcademicRecordDomain
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?string
    {
        return isset($this->endDate) ? $this->endDate?->format('Y-m-d') : null;
    }

    /**
     * @throws MustHaveEndDateWhenFinishedException
     * @throws EndDateMustBeAfterStartDateException
     */
    public function setEndDate(string|Carbon|null $date): self
    {
        if (empty($date)) {
            if (isset($this->isOnProgress) && !$this->isOnProgress) {
                throw new MustHaveEndDateWhenFinishedException();
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

        return $this;
    }

    public function isOnProgress(): bool
    {
        return $this->isOnProgress;
    }

    public function setIsOnProgress(bool $isOnProgress): AcademicRecordDomain
    {
        $this->isOnProgress = $isOnProgress;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): AcademicRecordDomain
    {
        $this->description = $description;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): AcademicRecordDomain
    {
        $this->id = $id;

        return $this;
    }

    public function exists(int $recordId): bool
    {
        return $this->repository->exists($recordId);
    }

    public function isOwner(int $recordId, int $userId): bool
    {
        return $this->repository->isOwner($recordId, $userId);
    }
}
