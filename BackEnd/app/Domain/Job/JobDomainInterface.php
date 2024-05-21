<?php

namespace App\Domain\Job;

use Illuminate\Support\Collection;

interface JobDomainInterface
{
    public function save(): self;

    public function update(): void;

    public function delete(): void;

    public function jobsWithIncludes(array $includes): array;

    public function toArray(): array;

    public function fromArray(array $job): self;

    public function getJobWithIncludes(array $includes): array;

    public function attachCompetences(Collection $competences): self;

    function acceptApplication(): self;

}
