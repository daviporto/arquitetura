<?php

namespace App\Domain\User;

use App\Domain\Competence\CompetencesIdFilterMustBePositiveIntegersException;
use App\Exceptions\User\UnknownUserIncludeException;
use App\Exceptions\User\UnknownUserTypeException;
use App\Exceptions\User\UserDoesntHaveCompetenceException;
use App\Exceptions\User\UserEmailNotAvailableException;
use App\Exceptions\User\UserPhoneNotAvailableException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class UserDomain implements UserDomainInterface
{
    private ?int $id;
    private string $name;
    private string $email;
    private string $phone;
    private ?string $password;
    private UserTypeEnum $type;
    private ?string $aboutMe;
    private ?string $profilePicturePath;


    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    )
    {
    }

    /**
     * @throws UserEmailNotAvailableException
     * @throws UserPhoneNotAvailableException
     */
    public function update(): self
    {
        if (!$this->userRepository->isEmailAvailableToUpdate($this)) {
            throw new UserEmailNotAvailableException($this->getEmail());
        }

        if (!$this->userRepository->isPhoneAvailable($this)) {
            throw new UserPhoneNotAvailableException($this->getPhone());
        }

        $this->userRepository->update($this);

        return (new UserDomain($this->getRepository()))->loadUser($this->getId());
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function loadUser(int $userId): self
    {
        $user = $this->userRepository->getUser($userId);
        $this->name = $user['name'];
        $this->email = $user['email'];
        $this->phone = $user['phone'];
        $this->aboutMe = $user['about_me'];
        $this->type = UserTypeEnum::from($user['type']);
        $this->profilePicturePath = $user['profile_picture_path'];

        empty($this->id) && $this->id = $user['id'];

        return $this;
    }

    public function getRepository(): UserRepositoryInterface
    {
        return $this->userRepository;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function createUser(): self
    {
        $created = $this->userRepository->createUser($this->toArray());

        return (new self($this->getRepository()))->fromArray($created);
    }

    public function toArray(): array
    {
        $user = [
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'phone' => $this->getPhone(),
        ];

        !empty($this->id) && $user['id'] = $this->id;
        !empty($this->password) && $user['password'] = $this->password;
        !empty($this->type) && $user['type'] = $this->type->value;
        !empty($this->aboutMe) && $user['about_me'] = $this->aboutMe;

        return $user;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @throws UnknownUserTypeException
     */
    public function fromArray(array $user): self
    {
        $this->setName($user['name']);
        $this->setEmail($user['email']);
        $this->setPhone($user['phone']);

        !empty($user['type']) && $this->setType($user['type']);
        !empty($user['id']) && $this->setId($user['id']);
        !empty($user['password']) && $this->setPassword($user['password']);
        !empty($user['about_me']) && $this->setAboutMe($user['about_me']);

        return $this;
    }

    public function setName(string $name): UserDomain
    {
        $this->name = $name;

        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @throws UnknownUserTypeException
     */
    public function setType(UserTypeEnum|string $type): self
    {
        if ($type instanceof UserTypeEnum) {
            $this->type = $type;
        } else {
            $enumType = UserTypeEnum::tryFrom($type);
            if (!$enumType) {
                throw new UnknownUserTypeException($type);
            }

            $this->type = $enumType;
        }

        return $this;
    }

    public function setId(?int $id): UserDomain
    {
        $this->id = $id;

        return $this;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function setAboutMe(?string $aboutMe): self
    {
        $this->aboutMe = $aboutMe;

        return $this;
    }

    public function exists(): bool
    {
        return $this->userRepository->exists($this->id);
    }

    public function users(): array
    {
        return $this->userRepository->getUsers();
    }

    public function attachCompetences(Collection $competences): self
    {
        $this->userRepository->attachCompetences($this->id, $competences);

        return $this;
    }

    public function getType(): UserTypeEnum
    {
        return $this->type;
    }

    /**
     * @throws UserDoesntHaveCompetenceException
     */
    public function removeCompetence(int $competenceId): self
    {
        if (!$this->userRepository->userHasCompetence($this->id, $competenceId)) {
            throw new UserDoesntHaveCompetenceException($this->id, $competenceId);
        }

        $this->userRepository->removeCompetence($this->id, $competenceId);

        return $this;
    }

    public function loadUserWithIncludes(int $userId, array $includes): array
    {
        return $this->userRepository->getUserWithIncludes($userId, $includes);
    }

    /**
     * @throws UnknownUserTypeException
     * @throws UnknownUserIncludeException
     * @throws CompetencesIdFilterMustBePositiveIntegersException
     */
    public function usersWithIncludes(array $filters = [], array $includes = []): array
    {
        $this->validateIncludes($includes);

        if (!empty($filters)) {
            $filters = (new UserFilters())->fromArray($filters);

            return $this->userRepository->getWithFilters($filters, $includes);
        }

        return $this->userRepository->getUsersWithIncludes($includes);
    }

    /**
     * @throws UnknownUserIncludeException
     */
    private function validateIncludes(array $includes): self
    {
        if (empty($includes)) {
            return $this;
        }

        $nonExistentIncludes = array_diff($includes, UserIncludesEnum::values());

        if ($nonExistentIncludes) {
            throw new UnknownUserIncludeException($nonExistentIncludes);
        }

        return $this;
    }

    public function updateProfilePicture(UploadedFile $profilePicture, int $userId): string
    {
        if (isset($this->profilePicturePath)) {
            $this->userRepository->deleteProfilePicture($this->profilePicturePath, $userId);
        }

        return $this->userRepository->createProfilePicture($profilePicture, $userId);
    }

    public function deleteProfilePicture(): void
    {
        $this->userRepository->deleteProfilePicture($this->getProfilePicturePath(), $this->getId());
    }

    public function getProfilePicturePath(): ?string
    {
        return $this->profilePicturePath;
    }

    public function createProfilePicture(UploadedFile $file, int $userId): void
    {
        $this->userRepository->createProfilePicture($file, $userId);
    }

    public function setProfilePicturePath(?string $profilePicturePath): UserDomain
    {
        $this->profilePicturePath = $profilePicturePath;

        return $this;
    }

    public function getAboutMe(): ?string
    {
        return $this->aboutMe;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
