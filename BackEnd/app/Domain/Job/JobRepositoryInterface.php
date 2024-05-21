<?php

namespace App\Domain\Job;

use Illuminate\Support\Collection;

interface JobRepositoryInterface
{
    public function createJob(JobDomainInterface $job): array;

    public function jobExists(int $id): bool;

    public function update(JobDomainInterface $job): void;

    public function getJobOwner(int $id): int;

    public function delete(int $id): void;

    public function getJobs(array $includes = []): array;

    public function getJob(?int $id);

    public function getJobWithIncludes(?int $id, array $includes): array;

    public function attachCompetences(int $id, Collection $competences);

    public function setNotAvailable(int $id): void;

    public function getApplicationAmount(int $id): int;
}
