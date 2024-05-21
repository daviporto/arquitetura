<?php

namespace App\Domain\Job;

use App\Domain\Abstract\AbstractRepository;
use App\Domain\Competence\CompetenceDomainInterface;
use App\Models\Job;
use DB;
use Illuminate\Support\Collection;

class JobRepository extends AbstractRepository implements JobRepositoryInterface
{
    public function createJob(JobDomainInterface $job): array
    {
        return Job::create($job->toArray())->toArray();
    }

    public function jobExists(int $id): bool
    {
        return Job::where('id', $id)->exists();
    }

    public function getJobOwner(int $id): int
    {
        return DB::table('jobs')
            ->where('id', $id)
            ->first('user_id')
            ->user_id;
    }

    public function delete(int $id): void
    {
        Job::where('id', $id)->delete();
    }

    public function update(JobDomainInterface $job): void
    {
        Job::where('id', $job->getId())->update($job->toArray());
    }

    public function getJobs(array $includes = []): array
    {
        return Job::with($includes)->get()->toArray();
    }

    public function getJob(?int $id): array
    {
        return Job::find($id)->toArray();
    }

    public function getJobWithIncludes(?int $id, array $includes): array
    {
        return Job::with($includes)->find($id)->toArray();
    }

    public function attachCompetences(int $id, Collection $competences): void
    {
        Job::where('id', $id)
            ->first()
            ->competences()
            ->attach(
                $competences->map(
                    fn(CompetenceDomainInterface $competence) => $competence->getId()
                )->toArray()
            );
    }

    public function setNotAvailable(int $id): void
    {
        Job::where('id', $id)->update(['is_available' => false]);
    }

    public function getApplicationAmount(int $id): int
    {
        return Job::find($id)->applications()->count();
    }
}
