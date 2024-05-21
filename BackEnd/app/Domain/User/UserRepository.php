<?php

namespace App\Domain\User;

use App\Domain\Abstract\AbstractRepository;
use App\Domain\Competence\CompetenceDomainInterface;
use App\Helpers\File\FileHelperInterface;
use App\Mail\UserForgotPassword;
use App\Models\User;
use DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Mail;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    public function createUser(array $user): array
    {
        $user['password'] = Hash::make($user['password']);

        return User::create($user)->toArray();
    }

    public function isEmailAvailableToUpdate(UserDomainInterface $user): bool
    {
        return User::where('id', "!=", $user->getId())
            ->where('email', $user->getEmail())
            ->doesntExist();
    }

    public function isPhoneAvailable(UserDomainInterface $user): bool
    {
        return User::where('id', '!=', $user->getId())
            ->where('phone', $user->getPhone())
            ->doesntExist();
    }

    public function update(UserDomainInterface $user): void
    {
        $array = $user->toArray();

        if (!empty($array['password'])) {
            $array['password'] = Hash::make($array['password']);
        }

        User::where('id', $user->getId())
            ->update($array);
    }

    public function forgotPassword(UserDomain $user): void
    {
        Mail::send(new UserForgotPassword($user));
    }

    public function getUsers(): array
    {
        return User::all()->toArray();
    }

    public function getUsersWithIncludes($includes): array
    {
        return User::with($includes)
            ->get()
            ->toArray();
    }

    public function exists(int $id): bool
    {
        return User::where('id', $id)->exists();
    }

    public function getUser(int $id): array
    {
        return User::find($id)->toArray();
    }

    public function getUserWithIncludes(int $userId, $includes): array
    {
        return User::where('id', $userId)
            ->with($includes)
            ->first()
            ->toArray();
    }

    public function attachCompetences(int $id, Collection $competences): void
    {
        User::where('id', $id)
            ->first()
            ->competences()
            ->attach(
                $competences->map(
                    fn(CompetenceDomainInterface $competence) => $competence->getId()
                )->toArray()
            );
    }

    public function removeCompetence(int $id, int $competenceId): void
    {
        DB::table('competence_user')
            ->where('user_id', $id)
            ->where('competence_id', $competenceId)
            ->delete();
    }

    public function userHasCompetence(?int $id, int $competenceId): bool
    {
        return DB::table('competence_user')
            ->where('user_id', $id)
            ->where('competence_id', $competenceId)
            ->exists();
    }

    public function createProfilePicture(UploadedFile $file, int $userId): string
    {
        $fileHelper = app(FileHelperInterface::class);

        $path = $fileHelper->storeRandomInPublicDirectory($file);

        User::where('id', $userId)
            ->update(['profile_picture_path' => $path]);

        return $path;
    }

    public function deleteProfilePicture(string $profilePicturePath, int $userId): void
    {
        $fileHelper = app(FileHelperInterface::class);

        $fileHelper->deletePublicFile($profilePicturePath);

        DB::table('users')
            ->where('id', $userId)
            ->update(['profile_picture_path' => null]);
    }

}
