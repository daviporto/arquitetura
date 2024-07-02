<?php

namespace App\Domain\Job;


use App\Domain\Competence\CompetenceDomainInterface;
use App\Http\Requests\Job\JobFiltersInterface;
use App\Models\Job;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class JobRepository implements JobRepositoryInterface
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
        return Job::with($includes)
            ->withCount('applications')
            ->get()
            ->toArray();
    }

    public function getJob(?int $id): array
    {
        return Job::find($id)->toArray();
    }

    public function getJobWithIncludes(?int $id, array $includes): array
    {
        return Job::with($includes)
            ->withCount('applications')
            ->find($id)
            ->toArray();
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

    public function getCompetences(int $id): array
    {
        return Job::find($id)->competences()->get()->toArray();
    }

    public function deleteCompetences(?int $id): void
    {
        Job::find($id)->competences()->detach();
    }

    public function getJobsWithFilters(JobFiltersInterface $filers, array $includes): array
    {
        $query = Job::query();

        $query->when($filers->getName(), fn($q) => $q->where('name', 'like', "%{$filers->getName()}%"));

        $query->when(
            $filers->getDescription(),
            fn($q) => $q->where('description', 'like', "%{$filers->getDescription()}%")
        );

        $query->when($filers->getIsAvailable(), fn($q) => $q->where('is_available', $filers->getIsAvailable()));

        $query->when($filers->getSalaryFrom(), fn($q) => $q->where('salary', '>=', $filers->getSalaryFrom()));
        $query->when($filers->getSalaryTo(), fn($q) => $q->where('salary', '<=', $filers->getSalaryTo()));

        $query->when(
            $filers->getSalaryTimeUnits(),
            fn($q) => $q->whereIn('salary_time_unit', $filers->getSalaryTimeUnits())
        );

        $query->when(
            $filers->getAcceptApplicationUntil(),
            fn(Builder $q) => $q->whereDate('accept_application_until', '<=', $filers->getAcceptApplicationUntil())
        );

        $query->when(
            $filers->getWorkModels(),
            fn($q) => $q->whereIn('work_model', $filers->getWorkModels())
        );

        $query->when(
            $filers->getEmploymentTypes(),
            fn($q) => $q->whereIn('employment_type', $filers->getEmploymentTypes())
        );

        $query->when(
            $filers->getWeekWorkloadFrom(),
            fn($q) => $q->where('week_workload', '>=', $filers->getWeekWorkloadFrom())
        );
        $query->when(
            $filers->getWeekWorkloadTo(),
            fn($q) => $q->where('week_workload', '<=', $filers->getWeekWorkloadTo())
        );

        $query->when(
            $filers->getLocation(),
            fn($q) => $q->where('location', 'like', "%{$filers->getLocation()}%")
        );

        $query->when(
            $filers->getCompanyIds(),
            fn($q) => $q->whereIn('company_id', $filers->getCompanyIds())
        );

        $query->when(
            $filers->getUserIds(),
            fn($q) => $q->whereIn('user_id', $filers->getUserIds())
        );

        $query->when(
            $filers->getCompetencesId(),
            fn($q) => $q->whereHas('competences', fn($q) => $q->whereIn('competence_id', $filers->getCompetencesId()))
        );

        return $query->with($includes)
            ->withCount('applications')
            ->get()
            ->toArray();
    }
}
