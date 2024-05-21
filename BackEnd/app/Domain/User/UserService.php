<?php

namespace App\Domain\User;


use App\Domain\Abstract\AbstractService;
use App\Domain\Company\CompanyDomain;
use App\Domain\Company\CompanyRepository;
use App\Domain\Competence\CompetenceDomain;
use App\Domain\Competence\CompetenceRepository;
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
use App\Exceptions\User\UserNotFoundException;
use App\Helpers\File\FileHelperInterface;
use Exception;
use Illuminate\Http\UploadedFile;
use Throwable;

class UserService extends AbstractService implements UserServiceInterface
{
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
        $repository = app(UserRepository::class);

        try {
            return (new UserDomain($repository))
                ->fromArray($user)
                ->update()
                ->toArray();

        } catch (Exception $exception) {
            $this->commonLogLogic($repository, $exception);

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
        $userRepository = new UserRepository();

        try {
            $userRepository->beginTransaction();

            $competenceDomain = new CompetenceDomain(new CompetenceRepository());

            $competences = $competenceDomain->createCompetencesIfNotExist($competences);

            (new UserDomain($userRepository))
                ->setId($userId)
                ->attachCompetences($competences);

            $userRepository->commitTransaction();
        } catch (Exception $exception) {
            $this->commonLogLogic($userRepository, $exception);

            throw $exception;
        }
    }

    /**
     * @throws Throwable
     */
    public function removeCompetences(int $userId, array $competencesId): void
    {
        $userRepository = new UserRepository();

        try {
            $userRepository->beginTransaction();
            $userDomain = new UserDomain($userRepository);
            $userDomain->setId($userId);

            $competenceRepository = new CompetenceRepository();

            foreach ($competencesId as $competenceId) {
                $competenceDomain = new CompetenceDomain($competenceRepository);
                if (!$competenceDomain->exists($competenceId)) {
                    throw new CompetenceNotFound($competenceId);
                }

                $userDomain->removeCompetence($competenceId);
            }

            $userRepository->commitTransaction();
        } catch (Exception $exception) {
            $this->commonLogLogic($userRepository, $exception);

            throw $exception;
        }
    }


    /**
     * @throws Throwable
     */
    public function addAcademicRecords(int $userId, array $records): void
    {
        $userRepository = new UserRepository();

        try {
            $userRepository->beginTransaction();

            $academicRecordDomain = new AcademicRecordDomain(new AcademicRecordRepository());

            $academicRecordDomain->createMany($records, $userId);
            $userRepository->commitTransaction();
        } catch (Exception $exception) {
            $this->commonLogLogic($userRepository, $exception);

            throw $exception;
        }
    }

    /**
     * @throws Throwable
     */
    public function removeAcademicRecords(int $userId, array $academicRecordsId): void
    {
        $academicRecordRepository = new AcademicRecordRepository();

        try {
            $academicRecordRepository->beginTransaction();

            foreach ($academicRecordsId as $recordId) {
                $academicRecordsDomain = new AcademicRecordDomain($academicRecordRepository);
                if (!$academicRecordsDomain->exists($recordId)) {
                    throw new AcademicRecordNotFoundException($recordId);
                }

                if (!$academicRecordsDomain->isOwner($recordId, $userId)) {
                    throw new OnlyOwnerCanDeleteAcademicRecordException($recordId);
                }

                $academicRecordRepository->delete($recordId);
            }

            $academicRecordRepository->commitTransaction();
        } catch (Exception $exception) {
            $this->commonLogLogic($academicRecordRepository, $exception);

            throw $exception;
        }
    }

    /**
     * @throws Throwable
     */
    public function removeProfessionalExperiences(int $userId, array $experiences): void
    {
        $repository = new ProfessionalExperienceRepository();

        try {
            $repository->beginTransaction();

            foreach ($experiences as $experience) {
                $domain = new ProfessionalExperienceDomain($repository);
                if (!$domain->exists($experience)) {
                    throw new ProfessionalExperienceNotFoundException($experience);
                }

                if (!$domain->isOwner($experience, $userId)) {
                    throw new OnlyOwnerCanDeleteProfessionalExperienceException($experience);
                }

                $domain->delete($experience);
            }

            $repository->commitTransaction();
        } catch (Exception $exception) {
            $this->commonLogLogic($repository, $exception);

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
        $userRepository = new UserRepository();

        try {
            $userRepository->beginTransaction();

            $experiencesDomain = new ProfessionalExperienceDomain(new ProfessionalExperienceRepository());

            $this->assureCompaniesExist($experiences);

            $experiencesDomain->createMany($experiences, $userId);

            $userRepository->commitTransaction();
        } catch (Exception $exception) {
            $this->commonLogLogic($userRepository, $exception);

            throw $exception;
        }
    }

    /**
     * @throws Throwable
     */
    public function setProfilePicture(UploadedFile $file, int $userId): void
    {
        $userRepository = new UserRepository();

        try {
            $userRepository->beginTransaction();

            $domain = new UserDomain($userRepository);

            $domain->createProfilePicture($file, $userId);

            $userRepository->commitTransaction();
        } catch (Exception $exception) {
            $this->commonLogLogic($userRepository, $exception);

            throw $exception;
        }
    }

    /**
     * @throws Throwable
     */
    public function updateProfilePicture(int $userId, UploadedFile $profilePicture): string
    {
        $userRepository = new UserRepository();

        try {
            $userRepository->beginTransaction();

            $domain = new UserDomain($userRepository);
            $domain->loadUser($userId);

            $path = $domain->updateProfilePicture($profilePicture, $userId);

            $userRepository->commitTransaction();

            return app(FileHelperInterface::class)->getUrlForPublicFile($path);
        } catch (Exception $exception) {
            $this->commonLogLogic($userRepository, $exception);

            throw $exception;
        }
    }

    /**
     * @throws Throwable
     */
    public function deleteProfilePicture(int $userId): void
    {
        $userRepository = new UserRepository();

        try {
            $userRepository->beginTransaction();

            $domain = new UserDomain($userRepository);
            $domain->loadUser($userId);

            if ($domain->getProfilePicturePath() === null) {
                return;
            }

            $domain->deleteProfilePicture();

            $userRepository->commitTransaction();

        } catch (Exception $exception) {
            $this->commonLogLogic($userRepository, $exception);

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
}
