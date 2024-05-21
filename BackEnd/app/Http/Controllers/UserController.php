<?php

namespace App\Http\Controllers;

use App\Domain\User\UserDomain;
use App\Domain\User\UserRepository;
use App\Domain\User\UserService;
use App\Exceptions\Abstract\AbstractFindMeException;
use App\Http\Requests\AbstractRequest;
use App\Http\Requests\User\ShowUserRequest;
use App\Http\Requests\User\UpdateUserProfilePictureRequest;
use App\Http\Requests\User\UpdateUserRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as IluminateResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index(ShowUserRequest $request): JsonResponse|IluminateResponse
    {
        $service = new UserDomain(app(UserRepository::class));

        try {
            return response()
                ->json([
                    'data' => $service->usersWithIncludes($request->getIncludes())
                ]);
        } catch (Exception $exception) {
            Log::error($exception);

            return response()
                ->json(
                    ['error' => 'Server error'],
                    Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(ShowUserRequest $request): JsonResponse|IluminateResponse
    {
        try {
            return response()->json([
                'data' => (new UserService())
                    ->getUser($request->getUserId(), $request->getIncludes())
            ]);
        } catch (Exception $exception) {
            Log::error($exception);

            return response()
                ->json(
                    ['error' => 'Server error'],
                    Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateUserRequest $request): JsonResponse|IluminateResponse
    {
        try {
            $user = (new UserService())
                ->update(
                    ['id' => $request->getLoggedUserId()] + $request->validated()
                );

            return response()->json([
                'data' => $user
            ]);
        } catch (AbstractFindMeException  $exception) {

            return response()->json(
                $exception->render(),
                status: $exception->getHttpCode()
            );
        } catch (Exception) {
            return response()
                ->json(
                    ['error' => 'Server error'],
                    Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateProfilePicture(UpdateUserProfilePictureRequest $request): IluminateResponse|JsonResponse
    {
        try {
            $url = (new UserService())
                ->updateProfilePicture(
                    $request->getLoggedUserId(), $request->file('profile_picture')
                );

            return response()->json([
                'url' => $url
            ]);
        } catch (Exception) {
            return response()
                ->json(
                    ['error' => 'Server error'],
                    Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteProfilePicture(AbstractRequest $request): IluminateResponse|JsonResponse
    {
        try {
            (new UserService())
                ->deleteProfilePicture($request->getLoggedUserId());

            return response()->noContent();
        } catch (Exception) {
            return response()
                ->json(
                    ['error' => 'Server error'],
                    Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
