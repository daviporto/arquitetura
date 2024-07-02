<?php

namespace App\Domain\User;



use App\Domain\Company\CompanyDomain;
use App\Domain\Company\CompanyRepository;
use App\Domain\Competence\CompetenceDomain;
use App\Domain\Competence\CompetenceRepository;
use App\Domain\Competence\CompetencesIdFilterMustBePositiveIntegersException;
use App\Domain\User\AcademicRecord\AcademicRecordDomain;
use App\Domain\User\AcademicRecord\AcademicRecordRepository;
use App\Domain\User\ProfessionalExperience\ProfessionalExperienceDomain;
use App\Domain\User\ProfessionalExperience\ProfessionalExperienceRepository;
use App\Exceptions\Abstract\AbstractFindMeException;
use App\Exceptions\Company\CompanyNotFoundException;
use App\Exceptions\Competence\CompetenceNotFound;
use App\Exceptions\User\AcademicRecord\AcademicRecordNotFoundException;
use App\Exceptions\User\AcademicRecord\OnlyOwnerCanDeleteAcademicRecordException;
use App\Exceptions\User\ProfessionalExperience\CurrentExperienceEndDateMustBeInTheFutureException;
use App\Exceptions\User\ProfessionalExperience\EndDateMustBeAfterStartDateException;
use App\Exceptions\User\ProfessionalExperience\MustHaveEndDateWhenFinishedExperienceException;
use App\Exceptions\User\ProfessionalExperience\OnlyOwnerCanDeleteProfessionalExperienceException;
use App\Exceptions\User\ProfessionalExperience\ProfessionalExperienceNotFoundException;
use App\Exceptions\User\UnknownUserIncludeException;
use App\Exceptions\User\UnknownUserTypeException;
use App\Exceptions\User\UserNotFoundException;
use App\Helpers\DataTransaction\DataTransactionServiceInterface;
use App\Helpers\File\FileHelperInterface;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Throwable;

class UserService implements UserServiceInterface
{
    private DataTransactionServiceInterface $dataTransactionService;

    public function __construct()
    {
        $this->dataTransactionService = app(DataTransactionServiceInterface::class);
    }

    public function createUser(array $user): int
    {
        return (new UserDomain(app(UserRepository::class)))
            ->fromArray($user)
            ->createUser()
            ->getId();
    }

    /**
     * @throws Throwable
     * @throws AbstractFindMeException
     */
    public function update(array $user): array
    {
        try {
            $this->dataTransactionService->begin();
            $updatedUser = (new UserDomain(new UserRepository()))
                ->fromArray($user)
                ->update()
                ->toArray();

            $this->dataTransactionService->commit();

            return $updatedUser;
        } catch (Exception $exception) {
            $this->dataTransactionService->rollback();
            Log::error($exception);

            throw $exception;
        }
    }

    /**
     * @throws UserNotFoundException
     */
    public function getUser(int $userId, array $includes): array
    {
        $repository = app(UserRepository::class);

        $userDomain = new UserDomain($repository);
        $userDomain->setId($userId);

        if (!$userDomain->exists()) {
            throw new UserNotFoundException($userId);
        }

        return $userDomain->loadUserWithIncludes($userId, $includes);
    }

    /**
     * @throws Throwable
     */
    public function addCompetencesToUser(int $userId, array $competences): void
    {
        try {
            $this->dataTransactionService->begin();

            $competenceDomain = new CompetenceDomain(new CompetenceRepository());

            $competences = $competenceDomain->createCompetencesIfNotExist($competences);

            (new UserDomain(new UserRepository()))
                ->setId($userId)
                ->attachCompetences($competences);

            $this->dataTransactionService->commit();
        } catch (Exception $exception) {
            $this->dataTransactionService->rollback();
            Log::error($exception);

            throw $exception;
        }
    }

    /**
     * @throws Throwable
     */
    public function removeCompetences(int $userId, array $competencesId): void
    {
        try {
            $this->dataTransactionService->begin();
            $userDomain = new UserDomain(new UserRepository());
            $userDomain->setId($userId);

            $competenceRepository = new CompetenceRepository();

            foreach ($competencesId as $competenceId) {
                $competenceDomain = new CompetenceDomain($competenceRepository);
                if (!$competenceDomain->exists($competenceId)) {
                    throw new CompetenceNotFound($competenceId);
                }

                $userDomain->removeCompetence($competenceId);
            }

            $this->dataTransactionService->commit();
        } catch (Exception $exception) {
            $this->dataTransactionService->rollback();
            Log::error($exception);

            throw $exception;
        }
    }


    /**
     * @throws Throwable
     */
    public function addAcademicRecords(int $userId, array $records): void
    {
        try {
            $this->dataTransactionService->begin();
            $academicRecordDomain = new AcademicRecordDomain(new AcademicRecordRepository());

            $academicRecordDomain->createMany($records, $userId);
            $this->dataTransactionService->commit();
        } catch (Exception $exception) {
            $this->dataTransactionService->rollback();
            Log::error($exception);

            throw $exception;
        }
    }

    /**
     * @throws Throwable
     */
    public function removeAcademicRecords(int $userId, array $academicRecordsId): void
    {
        try {
            $this->dataTransactionService->begin();

            foreach ($academicRecordsId as $recordId) {
                $academicRecordsDomain = new AcademicRecordDomain(new AcademicRecordRepository());
                if (!$academicRecordsDomain->exists($recordId)) {
                    throw new AcademicRecordNotFoundException($recordId);
                }

                if (!$academicRecordsDomain->isOwner($recordId, $userId)) {
                    throw new OnlyOwnerCanDeleteAcademicRecordException($recordId);
                }

                $academicRecordsDomain->delete($recordId);
            }

            $this->dataTransactionService->commit();
        } catch (Exception $exception) {
            $this->dataTransactionService->rollback();
            Log::error($exception);

            throw $exception;
        }
    }

    /**
     * @throws Throwable
     */
    public function removeProfessionalExperiences(int $userId, array $experiences): void
    {
        try {
            $this->dataTransactionService->begin();
            foreach ($experiences as $experience) {
                $domain = new ProfessionalExperienceDomain(new ProfessionalExperienceRepository());
                if (!$domain->exists($experience)) {
                    throw new ProfessionalExperienceNotFoundException($experience);
                }

                if (!$domain->isOwner($experience, $userId)) {
                    throw new OnlyOwnerCanDeleteProfessionalExperienceException($experience);
                }

                $domain->delete($experience);
            }

            $this->dataTransactionService->commit();
        } catch (Exception $exception) {
            $this->dataTransactionService->rollback();
            Log::error($exception);

            throw $exception;
        }
    }

    /**
     * @throws MustHaveEndDateWhenFinishedExperienceException
     * @throws Throwable
     * @throws EndDateMustBeAfterStartDateException
     * @throws CurrentExperienceEndDateMustBeInTheFutureException
     * @throws CompanyNotFoundException
     */
    public function addProfessionalExperiences(int $userId, array $experiences): void
    {
        try {
            $this->dataTransactionService->begin();
            $experiencesDomain = new ProfessionalExperienceDomain(new ProfessionalExperienceRepository());

            $this->assureCompaniesExist($experiences);

            $experiencesDomain->createMany($experiences, $userId);
            $this->dataTransactionService->commit();
        } catch (Exception $exception) {
            $this->dataTransactionService->rollback();
            Log::error($exception);

            throw $exception;
        }
    }

    /**
     * @throws Throwable
     */
    public function setProfilePicture(UploadedFile $file, int $userId): void
    {
        try {
            $this->dataTransactionService->begin();
            $domain = new UserDomain(new UserRepository());

            $domain->createProfilePicture($file, $userId);
            $this->dataTransactionService->commit();
        } catch (Exception $exception) {
            $this->dataTransactionService->rollback();
            Log::error($exception);

            throw $exception;
        }
    }

    /**
     * @throws Throwable
     */
    public function updateProfilePicture(int $userId, UploadedFile $profilePicture): string
    {
        try {
            $this->dataTransactionService->begin();

            $domain = new UserDomain(new UserRepository());
            $domain->loadUser($userId);

            $path = $domain->updateProfilePicture($profilePicture, $userId);

            $this->dataTransactionService->commit();

            return app(FileHelperInterface::class)->getUrlForPublicFile($path);
        } catch (Exception $exception) {
            $this->dataTransactionService->rollback();
            Log::error($exception);
            throw $exception;
        }
    }

    /**
     * @throws Throwable
     */
    public function deleteProfilePicture(int $userId): void
    {
        try {
            $this->dataTransactionService->begin();

            $domain = new UserDomain(new UserRepository());
            $domain->loadUser($userId);

            if ($domain->getProfilePicturePath() === null) {
                return;
            }

            $domain->deleteProfilePicture();

            $this->dataTransactionService->commit();
        } catch (Exception $exception) {
            $this->dataTransactionService->rollback();
            Log::error($exception);

            throw $exception;
        }
    }

    /**
     * @throws CompanyNotFoundException
     */
    private function assureCompaniesExist(array $experiences): void
    {
        $companyDomain = new CompanyDomain(new CompanyRepository());

        array_walk($experiences, function ($experience) use ($companyDomain) {
            if (isset($experience['company_id']) && !$companyDomain->exists($experience['company_id'])) {
                throw new CompanyNotFoundException($experience['company_id']);
            }
        });
    }

    /**
     * @throws UnknownUserTypeException
     * @throws UnknownUserIncludeException
     * @throws CompetencesIdFilterMustBePositiveIntegersException
     */
    public function usersWithIncludes(array $filters = [], array $includes = []): array
    {
        return (new UserDomain(app(UserRepository::class)))
            ->usersWithIncludes($filters, $includes);
    }
}
