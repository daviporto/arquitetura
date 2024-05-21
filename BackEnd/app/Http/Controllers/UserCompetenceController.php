<?php

namespace App\Http\Controllers;

use App\Domain\User\UserService;
use App\Exceptions\Abstract\AbstractFindMeException;
use App\Http\Requests\User\UserCompetence\AddCompetencesRequest;
use App\Http\Requests\User\UserCompetence\DeleteCompetencesRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as IluminateResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UserCompetenceController extends Controller
{
    public function addCompetences(AddCompetencesRequest $request): JsonResponse|IluminateResponse
    {
        try {
            app(UserService::class)
                ->addCompetencesToUser(
                    $request->getLoggedUserId(),
                    $request->validated('competences')
                );

            return response()->noContent();
        } catch (Exception $exception) {
            Log::error($exception);

            return response()
                ->json(
                    ['error' => 'Server error'],
                    Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteCompetences(DeleteCompetencesRequest $request): JsonResponse|IluminateResponse
    {
        try {
            (new UserService())
                ->removeCompetences(
                    $request->getLoggedUserId(),
                    $request->validated('competences_id')
                );

            return response()->noContent();
        } catch (AbstractFindMeException  $exception) {
            return response()->json(
                $exception->render(),
                status: $exception->getHttpCode()
            );
        } catch (Exception $exception) {
            Log::error($exception);

            return response()
                ->json(
                    ['error' => 'Server error'],
                    Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
