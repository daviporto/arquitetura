<?php

namespace App\Domain\Job;


use App\Domain\Company\CompanyDomain;
use App\Domain\Company\CompanyRepository;
use App\Domain\Competence\CompetenceDomain;
use App\Domain\Competence\CompetenceRepository;
use App\Domain\Competence\CompetencesIdFilterMustBePositiveIntegersException;
use App\Exceptions\Company\CompanyNotFoundException;
use App\Exceptions\Job\CompanyIdsFilterMustBePositiveIntegersException;
use App\Exceptions\Job\IdRequiredToUpdateException;
use App\Exceptions\Job\InvalidAcceptApplicationUntilDateFormatException;
use App\Exceptions\Job\JobNotFoundException;
use App\Exceptions\Job\OnlyOwnerCanUpdateJobException;
use App\Exceptions\Job\SalaryToMustBeBiggerThanFromException;
use App\Exceptions\Job\UnknownEmploymentTypesFilterException;
use App\Exceptions\Job\UnknownSalaryTimeUnitsFilterException;
use App\Exceptions\Job\UnknownWorkModelsFilterException;
use App\Exceptions\Job\UserIdsFilterMustBePositiveIntegersException;
use App\Exceptions\Job\WeekWorkloadMustBePositiveException;
use App\Exceptions\Job\WeekWorkloadToMustBeBiggerThanFromException;
use App\Helpers\DataTransaction\DataTransactionService;
use Exception;
use Throwable;

class JobService implements JobServiceInterface
{
    /**
     * @throws Throwable
     * @throws CompanyNotFoundException
     */
    public function store(array $data, int $userId): void
    {
        $dataTransaction = app(DataTransactionService::class);

        try {
            $dataTransaction->begin();

            $domain = new JobDomain(new JobRepository());
            $domain->fromArray($data + ['user_id' => $userId]);

            if (!(new CompanyDomain(new CompanyRepository()))->exists($domain->getCompanyId())) {
                throw new CompanyNotFoundException($domain->getCompanyId());
            }

            $domain = $domain->save();

            $this->createCompetences($data, $domain);

            $dataTransaction->commit();
        } catch (Exception $exception) {
            $dataTransaction->rollback();

            throw $exception;
        }
    }

    /**
     * @throws Throwable
     * @throws CompanyNotFoundException
     */
    public function update(array $data, int $userId, int $jobId): void
    {
        $dataTransactionService = app(DataTransactionService::class);
        $dataTransactionService->begin();

        try {
            $domain = new JobDomain(new JobRepository());

            $domain
                ->assureExists($jobId)
                ->fromArray(
                    $data + [
                        'user_id' => $userId,
                        'id' => $jobId
                    ])->assureExists($jobId);

            if (!(new CompanyDomain(new CompanyRepository()))->exists($domain->getCompanyId())) {
                throw new CompanyNotFoundException($domain->getCompanyId());
            }

            $domain->update()
                ->deleteCompetences();

            $this->createCompetences($data, $domain);

            $dataTransactionService->commit();
        } catch (Throwable $exception) {
            $dataTransactionService->rollback();

            throw $exception;
        }
    }

    /**
     * @param array $data
     * @param JobDomain $domain
     * @return void
     */
    public function createCompetences(array $data, JobDomain $domain): void
    {
        if (array_key_exists('competences', $data)) {
            $competenceDomain = new CompetenceDomain(new CompetenceRepository());

            $competences = $competenceDomain->createCompetencesIfNotExist($data['competences']);

            $domain->attachCompetences($competences);
        }
    }

    /**
     * @throws Throwable
     * @throws JobNotFoundException
     * @throws IdRequiredToUpdateException
     * @throws OnlyOwnerCanUpdateJobException
     */
    public function destroy(int $jobId, int $solicitorId): void
    {
        $dataTransactionService = app(DataTransactionService::class);
        $dataTransactionService->begin();

        try {
            $repository = app(JobRepository::class);

            $domain = new JobDomain($repository);
            $domain->setId($jobId)
                ->setUserId($solicitorId);

            if (!$domain->exists($domain->getId())) {
                throw new JobNotFoundException($domain->getId());
            }
            $domain->delete();

            $dataTransactionService->commit();
        } catch (Throwable $exception) {
            $dataTransactionService->rollback();

            throw $exception;
        }
    }

    /**
     * @throws JobNotFoundException
     */
    public function show(int $jobId, array $includes = []): array
    {
        return (new JobDomain(new JobRepository()))
            ->assureExists($jobId)
            ->setId($jobId)
            ->getJobWithIncludes($includes);
    }

    /**
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
    public function index(array $filers, array $includes): array
    {
        return (new JobDomain(new JobRepository()))
            ->jobsWithIncludes($filers, $includes);
    }
}
